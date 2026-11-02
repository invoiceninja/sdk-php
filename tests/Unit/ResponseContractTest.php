<?php

namespace InvoiceNinja\Sdk\Tests\Unit;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\NoSeekStream;
use GuzzleHttp\Psr7\Utils;
use InvoiceNinja\Sdk\Exceptions\ApiException;
use InvoiceNinja\Sdk\InvoiceNinja;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ResponseContractTest extends TestCase
{
    #[DataProvider('invalidJsonResponses')]
    public function testInvalidJsonHasAnSdkExceptionWithStatusAndDecoderCause(string $body, int $json_error): void
    {
        $sdk = $this->sdk(new Response(201, [], $body));

        try {
            $sdk->send('post', '/api/v1/clients');
            self::fail('Expected invalid JSON to fail at the transport boundary.');
        } catch (ApiException $exception) {
            self::assertSame(201, $exception->getCode());
            self::assertSame('POST /api/v1/clients (HTTP 201): Invalid JSON response.', $exception->getMessage());
            self::assertInstanceOf(\JsonException::class, $exception->getPrevious());
            self::assertSame($json_error, $exception->getPrevious()->getCode());
        }
    }

    public static function invalidJsonResponses(): iterable
    {
        yield 'empty body' => ['', JSON_ERROR_SYNTAX];
        yield 'whitespace only' => [" \r\n\t", JSON_ERROR_SYNTAX];
        yield 'HTML' => ['<html>private diagnostic</html>', JSON_ERROR_SYNTAX];
        yield 'truncated JSON' => ['{"data":', JSON_ERROR_SYNTAX];
        yield 'invalid UTF-8' => ["{\"name\":\"\xff\"}", JSON_ERROR_UTF8];
        yield 'decoder depth exceeded' => [str_repeat('[', 512) . '0' . str_repeat(']', 512), JSON_ERROR_DEPTH];
    }

    #[DataProvider('scalarRoots')]
    public function testScalarRootsAreRejected(string $body): void
    {
        $sdk = $this->sdk(new Response(200, [], $body));

        try {
            $sdk->send('GET', '/api/v1/clients');
            self::fail('A scalar cannot satisfy an array response contract.');
        } catch (ApiException $exception) {
            self::assertSame(200, $exception->getCode());
            self::assertStringContainsString('Expected a JSON object or list.', $exception->getMessage());
            self::assertNull($exception->getPrevious());
        }
    }

    public static function scalarRoots(): iterable
    {
        foreach (['null', 'true', 'false', '0', '1.5', '"text"', '""', '18446744073709551616'] as $body) {
            yield $body => [$body];
        }
    }

    public function testBigIntegersAndNumericObjectKeysRetainTheirValuesWithoutRequiringAJsonHeader(): void
    {
        $sdk = $this->sdk(new Response(202, ['Content-Type' => 'text/plain'],
            '{"0":{"id":18446744073709551616,"negative":-18446744073709551616,"small":42},"list":[null,false,1.25]}'));

        self::assertSame([
            0 => ['id' => '18446744073709551616', 'negative' => '-18446744073709551616', 'small' => 42],
            'list' => [null, false, 1.25],
        ], $sdk->send('GET', '/api/v1/clients'));
    }

    #[DataProvider('bodylessResponses')]
    public function testBodylessSuccessHasAnExplicitEmptyResult(string $operation, string $method, int $status): void
    {
        $sdk = $this->sdk(new Response($status));

        self::assertSame($operation === 'send' ? [] : '', $sdk->{$operation}($method, '/api/v1/clients'));
    }

    #[DataProvider('bodylessResponses')]
    public function testContradictoryBodiesAreRejected(string $operation, string $method, int $status): void
    {
        $sdk = $this->sdk(new Response($status, [], '{}'));

        try {
            $sdk->{$operation}($method, '/api/v1/clients');
            self::fail('A bodyless response must not contain payload bytes.');
        } catch (ApiException $exception) {
            self::assertSame($status, $exception->getCode());
            self::assertStringContainsString('Unexpected body for a bodyless response.', $exception->getMessage());
        }
    }

    public static function bodylessResponses(): iterable
    {
        foreach (['send', 'stream'] as $operation) {
            foreach ([['head', 200], ['HEAD', 201], ['HEAD', 204], ['GET', 204], ['POST', 205]] as [$method, $status]) {
                yield "$operation $method $status" => [$operation, $method, $status];
            }
        }
    }

    #[DataProvider('httpStatuses')]
    public function testUnsuccessfulStatusesFailWithOrWithoutGuzzleHttpErrors(string $operation, int $status): void
    {
        // Test a global opt-out and an opt-out on an individual request as well as the default middleware.
        foreach ([[], ['http_errors' => false], ['http_errors' => true]] as $options) {
            $payload = $options === ['http_errors' => true] ? ['http_errors' => false] : [];
            $sdk = $this->sdk(new Response($status, [], '{"message":"Request rejected"}'), $options);

            try {
                $sdk->{$operation}('GET', '/api/v1/clients', $payload);
                self::fail('Every final non-2xx status must fail.');
            } catch (ApiException $exception) {
                self::assertSame($status, $exception->getCode());
                self::assertStringContainsString("GET /api/v1/clients (HTTP $status)", $exception->getMessage());
                self::assertStringContainsString('Request rejected', $exception->getMessage());
                if ($status >= 400 && $options === []) {
                    self::assertInstanceOf(GuzzleException::class, $exception->getPrevious());
                } else {
                    self::assertNull($exception->getPrevious());
                }
            }
        }
    }

    public static function httpStatuses(): iterable
    {
        foreach (['send', 'stream'] as $operation) {
            foreach ([101, 199, 300, 302, 304, 307, 308, 400, 401, 403, 404, 422, 429, 500, 502, 503] as $status) {
                yield "$operation $status" => [$operation, $status];
            }
        }
    }

    #[DataProvider('errorBodies')]
    public function testErrorBodyNeverBecomesASuccessOrLeaksRawContent(string $operation, string $body): void
    {
        foreach ([true, false] as $http_errors) {
            $sdk = $this->sdk(new Response(502, [], $body), ['http_errors' => $http_errors]);

            try {
                $sdk->{$operation}('GET', '/api/v1/clients');
                self::fail('Expected an HTTP error even with an incompatible error body.');
            } catch (ApiException $exception) {
                self::assertSame(502, $exception->getCode());
                self::assertStringContainsString('Unable to decode response', $exception->getMessage());
                self::assertStringNotContainsString('private diagnostic', $exception->getMessage());
                if ($http_errors) {
                    self::assertInstanceOf(GuzzleException::class, $exception->getPrevious());
                }
            }
        }
    }

    public static function errorBodies(): iterable
    {
        foreach (['send', 'stream'] as $operation) {
            foreach (['', '<html>private diagnostic</html>', 'null', '[]', '"private diagnostic"', "{\"name\":\"\xff\"}"] as $i => $body) {
                yield "$operation body $i" => [$operation, $body];
            }
        }
    }

    public function testMalformedHttpErrorRetainsTheDecoderCauseWhenMiddlewareIsDisabled(): void
    {
        $sdk = $this->sdk(new Response(500, [], 'not json'), ['http_errors' => false]);

        try {
            $sdk->send('GET', '/api/v1/clients');
            self::fail('Expected an API exception.');
        } catch (ApiException $exception) {
            self::assertSame(500, $exception->getCode());
            self::assertInstanceOf(\JsonException::class, $exception->getPrevious());
        }
    }

    #[DataProvider('diagnosticOperations')]
    public function testDiagnosticsAreBoundedRedactedAndDoNotEchoBodiesOrSensitiveUrlParts(string $operation, bool $http_errors): void
    {
        $token = 'fixture/token+secret';
        $body = json_encode(['message' => "Rejected $token encoded " . rawurlencode($token)
            . ' bearer-secret request-secret client-secret https://user:password@example.test?secret=query-secret#fragment-secret' . "\r\n" . str_repeat('x', 2000), 'private' => 'private diagnostic']);
        $sdk = (new InvoiceNinja($token))->setUrl('https://user:password@example.test')
            ->addHeader(['Authorization' => 'bearer-secret'])
            ->setOptions([
                'handler' => HandlerStack::create(new MockHandler([new Response(401, [], $body)])),
                'http_errors' => $http_errors,
                'headers' => ['X-Client-Secret' => 'client-secret'],
            ]);

        try {
            $sdk->{$operation}('GET', '/api/v1/clients?secret=query-secret#fragment-secret', [
                'headers' => ['X-Custom-Secret' => ['request-secret']],
            ]);
            self::fail('Expected an authentication failure.');
        } catch (ApiException $exception) {
            $message = $exception->getMessage();
            self::assertSame(401, $exception->getCode());
            self::assertStringContainsString('GET /api/v1/clients (HTTP 401)', $message);
            self::assertStringContainsString('[redacted]', $message);
            self::assertLessThanOrEqual(512, strlen($message));
            self::assertDoesNotMatchRegularExpression('/[\x00-\x1f\x7f]/', $message);
            foreach ([$token, rawurlencode($token), 'password', 'example.test', 'query-secret', 'fragment-secret',
                'bearer-secret', 'request-secret', 'client-secret', 'private diagnostic'] as $secret) {
                self::assertStringNotContainsString($secret, $message);
            }
        }
    }

    public static function diagnosticOperations(): iterable
    {
        foreach (['send', 'stream'] as $operation) {
            foreach ([true, false] as $http_errors) {
                yield "$operation http_errors " . (int) $http_errors => [$operation, $http_errors];
            }
        }
    }

    public function testTransportFailureKeepsCauseWithoutEchoingGuzzleUrlOrBody(): void
    {
        $failure = new ConnectException('https://user:password@example.test?secret=private diagnostic', new Request('GET', 'https://example.test'));
        $sdk = (new InvoiceNinja(''))->setOptions(['handler' => HandlerStack::create(new MockHandler([$failure]))]);

        try {
            $sdk->send('GET', '/api/v1/clients?token=secret');
            self::fail('Expected a transport failure.');
        } catch (ApiException $exception) {
            self::assertSame('GET /api/v1/clients (HTTP 0): Transport failure.', $exception->getMessage());
            self::assertSame(0, $exception->getCode());
            self::assertSame($failure, $exception->getPrevious());
        }
    }

    #[DataProvider('emptyRawResponses')]
    public function testEmptyRawResponsesRemainStrings(int $status): void
    {
        self::assertSame('', $this->sdk(new Response($status))->stream('GET', '/api/v1/invoices/bulk'));
    }

    public static function emptyRawResponses(): iterable
    {
        yield [200];
        yield [201];
        yield [205];
    }

    #[DataProvider('nonSeekableResponses')]
    public function testNonSeekableBodiesAreConsumedOnce(string $operation, string $body, array|string $expected): void
    {
        $stream = new NoSeekStream(Utils::streamFor($body));
        $sdk = $this->sdk(new Response(200, [], $stream));

        self::assertSame($expected, $sdk->{$operation}('GET', '/api/v1/clients'));
        self::assertTrue($stream->eof());
    }

    public static function nonSeekableResponses(): iterable
    {
        yield 'JSON' => ['send', '{"data":[]}', ['data' => []]];
        yield 'binary' => ['stream', "%PDF-fixture\x00\xff", "%PDF-fixture\x00\xff"];
    }

    public function testUnsuccessfulHeadResponseIsNotTreatedAsAnEmptySuccess(): void
    {
        $sdk = $this->sdk(new Response(401), ['http_errors' => false]);

        $this->expectException(ApiException::class);
        $this->expectExceptionCode(401);
        $sdk->send('HEAD', '/api/v1/clients');
    }

    public function testEmpty200JsonResponseDoesNotMasqueradeAsAnEmptyArray(): void
    {
        $this->expectException(ApiException::class);
        $this->expectExceptionCode(200);
        $this->expectExceptionMessage('Invalid JSON response.');
        $this->sdk(new Response(200))->send('GET', '/api/v1/clients');
    }

    private function sdk(Response $response, array $options = []): InvoiceNinja
    {
        return (new InvoiceNinja('fixture'))->setOptions(array_merge([
            'handler' => HandlerStack::create(new MockHandler([$response])),
        ], $options));
    }
}
