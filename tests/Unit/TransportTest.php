<?php

namespace InvoiceNinja\Sdk\Tests\Unit;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use InvoiceNinja\Sdk\Exceptions\ApiException;
use InvoiceNinja\Sdk\InvoiceNinja;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class TransportTest extends TestCase
{
    public function testOptionsHeadersTokenAndMethodNormalization(): void
    {
        $history = [];
        $stack = HandlerStack::create(new MockHandler([
            new Response(200, [], '{"data":{"id":"fixture"}}'),
            new Response(200, [], '%PDF-fixture'),
        ]));
        $stack->push(Middleware::history($history));
        $sdk = (new InvoiceNinja('initial'))
            ->setUrl('https://example.test///')
            ->setOptions(['handler' => $stack, 'timeout' => 5])
            ->setOptions(['connect_timeout' => 2])
            ->setToken('replacement')
            ->addHeader(['X-Custom' => 'first'])
            ->addHeader(['X-Custom' => 'second']);

        self::assertSame('replacement', $sdk->getToken());
        self::assertSame(['data' => ['id' => 'fixture']], $sdk->send('get', '/api/v1/clients', ['query' => ['page' => 2]]));
        self::assertSame('%PDF-fixture', $sdk->stream('post', '/api/v1/invoices/bulk', ['form_params' => ['action' => 'download']]));

        $request = $history[0]['request'];
        self::assertSame('GET', $request->getMethod());
        self::assertSame('https://example.test/api/v1/clients?page=2', (string) $request->getUri());
        self::assertSame('replacement', $request->getHeaderLine('X-API-TOKEN'));
        self::assertSame('XMLHttpRequest', $request->getHeaderLine('X-Requested-With'));
        self::assertSame('second', $request->getHeaderLine('X-Custom'));
        self::assertSame(5, $history[0]['options']['timeout']);
        self::assertSame(2, $history[0]['options']['connect_timeout']);
        self::assertSame('POST', $history[1]['request']->getMethod());
        self::assertSame('action=download', (string) $history[1]['request']->getBody());
    }

    public function testHostedUrlAndDefaultPayload(): void
    {
        $history = [];
        $stack = HandlerStack::create(new MockHandler([new Response(200, [], '{}')]));
        $stack->push(Middleware::history($history));
        $sdk = (new InvoiceNinja('fixture'))->setOptions(['handler' => $stack]);

        self::assertSame([], $sdk->send('GET', '/api/v1/clients'));
        self::assertSame('https://invoicing.co/api/v1/clients', (string) $history[0]['request']->getUri());
    }

    #[DataProvider('jsonContainers')]
    public function testValidJsonContainersRetainResponseValues(string $body, array $expected): void
    {
        $sdk = (new InvoiceNinja('fixture'))->setOptions([
            'handler' => HandlerStack::create(new MockHandler([new Response(200, [], $body)])),
        ]);

        self::assertSame($expected, $sdk->send('GET', '/api/v1/clients'));
    }

    public static function jsonContainers(): iterable
    {
        yield 'empty object' => ['{}', []];
        yield 'empty list' => ['[]', []];
        yield 'root list' => ['[{"id":"first"},{"id":"second"}]', [['id' => 'first'], ['id' => 'second']]];
        yield 'pagination and empty results' => ['{"data":[],"meta":{"pagination":{"total":0,"current_page":1}}}', [
            'data' => [], 'meta' => ['pagination' => ['total' => 0, 'current_page' => 1]],
        ]];
        yield 'nested value types' => ['{"data":{"name":"Caf\u00e9","balance":12.5,"enabled":true,"notes":null,"id":"18446744073709551616"}}', [
            'data' => ['name' => 'Café', 'balance' => 12.5, 'enabled' => true, 'notes' => null, 'id' => '18446744073709551616'],
        ]];
    }

    #[DataProvider('rawBodies')]
    public function testRawDownloadsRemainStrings(int $status, string $body): void
    {
        $sdk = (new InvoiceNinja('fixture'))->setOptions([
            'handler' => HandlerStack::create(new MockHandler([new Response($status, [], $body)])),
        ]);

        self::assertSame($body, $sdk->stream('GET', '/api/v1/invoices/bulk'));
    }

    public static function rawBodies(): iterable
    {
        yield 'binary download' => [200, "%PDF-fixture\x00\xff\xfe"];
        yield 'JSON-looking download is not decoded' => [200, '{"data":[]}'];
        yield 'empty download' => [204, ''];
    }

    public function testRequestOptionsOverrideClientDefaultsAndTlsVerificationRemainsEnabled(): void
    {
        $history = [];
        $stack = HandlerStack::create(new MockHandler([new Response(200, [], '{}')]));
        $stack->push(Middleware::history($history));
        $sdk = (new InvoiceNinja('fixture'))->setOptions(['handler' => $stack, 'timeout' => 5, 'connect_timeout' => 2]);

        $sdk->send('GET', '/api/v1/clients', ['timeout' => 0.5, 'connect_timeout' => 0.25]);

        self::assertSame(0.5, $history[0]['options']['timeout']);
        self::assertSame(0.25, $history[0]['options']['connect_timeout']);
        self::assertTrue($history[0]['options']['verify']);
    }

    #[DataProvider('responseErrors')]
    public function testResponseErrorsBecomeApiExceptions(string $operation, int $status, string $body, string $message): void
    {
        foreach ([true, false] as $http_errors) {
            $sdk = (new InvoiceNinja('fixture'))->setOptions([
                'handler' => HandlerStack::create(new MockHandler([new Response($status, [], $body)])),
                'http_errors' => $http_errors,
            ]);

            try {
                $sdk->{$operation}('GET', '/api/v1/clients');
                self::fail('Expected an API exception.');
            } catch (ApiException $exception) {
                self::assertSame($status, $exception->getCode());
                self::assertStringContainsString($message, $exception->getMessage());
            }
        }
    }

    public static function responseErrors(): iterable
    {
        foreach (['send', 'stream', 'streamAsObject'] as $operation) {
            yield $operation . ' message' => [$operation, 403, '{"message":"Forbidden"}', 'Forbidden'];
            yield $operation . ' unauthenticated' => [$operation, 401, '{"message":"Unauthenticated"}', 'Unauthenticated'];
            yield $operation . ' rate limited' => [$operation, 429, '{"message":"Too many requests"}', 'Too many requests'];
            yield $operation . ' service unavailable' => [$operation, 503, '{"message":"Unavailable"}', 'Unavailable'];
            yield $operation . ' validation' => [$operation, 422, '{"message":"Invalid","errors":{"name":["Name is required"]}}', 'Name is required'];
            yield $operation . ' empty errors' => [$operation, 422, '{"message":"Invalid","errors":{}}', 'Invalid'];
            yield $operation . ' empty field errors' => [$operation, 422, '{"message":"Invalid","errors":{"name":[]}}', 'Invalid'];
            yield $operation . ' non-string field error' => [$operation, 422, '{"message":"Invalid","errors":{"name":[123]}}', 'Invalid'];
            yield $operation . ' non-string message' => [$operation, 500, '{"message":{"unexpected":true}}', 'Error executing API call ('];
            yield $operation . ' invalid error shape' => [$operation, 422, '{"message":"Invalid","errors":{"name":"wrong shape"}}', 'Invalid'];
            yield $operation . ' array errors' => [$operation, 422, '{"message":"Invalid","errors":[]}', 'Invalid'];
            yield $operation . ' no message' => [$operation, 500, '{}', 'Error executing API call ()'];
            foreach (['<html>Bad gateway</html>', 'null', '[]', '"scalar"'] as $body) {
                yield $operation . ' non-object ' . $body => [$operation, 502, $body, 'Unable to decode response'];
            }
        }
    }

    #[DataProvider('failures')]
    public function testConnectionFailuresPreserveCause(string $operation, string $exception_class): void
    {
        $failure = new $exception_class('Connection timed out', new Request('GET', 'https://example.test'));
        $sdk = (new InvoiceNinja('fixture'))->setOptions([
            'handler' => HandlerStack::create(new MockHandler([$failure])),
        ]);

        try {
            $sdk->{$operation}('GET', '/api/v1/clients');
            self::fail('Expected a connection failure.');
        } catch (ApiException $exception) {
            self::assertStringContainsString('GET /api/v1/clients (HTTP 0): Transport failure.', $exception->getMessage());
            self::assertSame($failure, $exception->getPrevious());
        }
    }

    public static function failures(): iterable
    {
        foreach (['send', 'stream', 'streamAsObject'] as $operation) {
            yield $operation . ' connection' => [$operation, ConnectException::class];
            yield $operation . ' request without response' => [$operation, RequestException::class];
        }
    }

    public function testSameOriginRedirectsRetainAuthenticationAndResolveLocation(): void
    {
        $history = [];
        $stack = HandlerStack::create(new MockHandler([
            new Response(302, ['Location' => '/api/v1/clients?page=2']),
            new Response(200, [], '{"data":[]}'),
        ]));
        $stack->push(Middleware::history($history));
        $sdk = (new InvoiceNinja('fixture'))->setUrl('https://example.test')->setOptions(['handler' => $stack]);

        self::assertSame(['data' => []], $sdk->send('GET', '/api/v1/clients'));
        self::assertCount(2, $history);
        self::assertSame('https://example.test/api/v1/clients?page=2', (string) $history[1]['request']->getUri());
        self::assertSame('fixture', $history[1]['request']->getHeaderLine('X-API-TOKEN'));
    }

    public function testRedirectFollowingCanBeDisabled(): void
    {
        $history = [];
        $stack = HandlerStack::create(new MockHandler([new Response(302, ['Location' => '/other'], '{}')]));
        $stack->push(Middleware::history($history));
        $sdk = (new InvoiceNinja('fixture'))->setOptions(['handler' => $stack, 'allow_redirects' => false]);

        try {
            $sdk->send('GET', '/api/v1/clients');
            self::fail('An unfollowed redirect must not be reported as success.');
        } catch (ApiException $exception) {
            self::assertSame(302, $exception->getCode());
        }
        self::assertCount(1, $history);
    }

    #[DataProvider('untrustedRedirects')]
    public function testDisablingRedirectsPreventsAnyRequestToAnotherOrigin(string $location): void
    {
        $history = [];
        $mock = new MockHandler([
            new Response(302, ['Location' => $location], '{}'),
            new Response(200, [], '{}'),
        ]);
        $stack = HandlerStack::create($mock);
        $stack->push(Middleware::history($history));
        $sdk = (new InvoiceNinja('fixture'))->setUrl('https://example.test')->setOptions([
            'handler' => $stack, 'allow_redirects' => false,
        ]);

        try {
            $sdk->send('GET', '/api/v1/clients');
            self::fail('An unfollowed redirect must not be reported as success.');
        } catch (ApiException $exception) {
            self::assertSame(302, $exception->getCode());
        }
        self::assertCount(1, $history);
        self::assertCount(1, $mock, 'The redirect destination response must remain unused.');
        self::assertSame('https://example.test/api/v1/clients', (string) $history[0]['request']->getUri());
    }

    public static function untrustedRedirects(): iterable
    {
        yield 'different host' => ['https://another.example.test/path'];
        yield 'scheme downgrade' => ['http://example.test/path'];
        yield 'different port' => ['https://example.test:8443/path'];
    }

}
