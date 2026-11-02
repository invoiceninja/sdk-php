<?php

namespace InvoiceNinja\Sdk\Tests\Unit;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use InvoiceNinja\Sdk\Exceptions\ApiException;
use InvoiceNinja\Sdk\InvoiceNinja;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class EndpointsTest extends TestCase
{
    #[DataProvider('entities')]
    public function testEntityRequests(string $property): void
    {
        $history = [];
        $responses = array_map(static fn () => new Response(200, [], '{"data":{"id":"fixture"}}'), range(1, 5));
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($history));
        $sdk = (new InvoiceNinja('fixture'))->setUrl('https://example.test')->setOptions(['handler' => $stack]);
        $entity = $sdk->{$property};

        self::assertSame('fixture', $entity->all(['page' => 2])['data']['id']);
        self::assertSame('fixture', $entity->get('fixture', ['include' => 'documents'])['data']['id']);
        self::assertSame('fixture', $entity->create(['name' => 'SDK fixture'], ['mark_sent' => 'true'])['data']['id']);
        self::assertSame('fixture', $entity->update('fixture', ['name' => 'Updated'])['data']['id']);

        $base = 'https://example.test/api/v1/' . $property;
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame($base . '?page=2', (string) $history[0]['request']->getUri());
        self::assertSame($base . '/fixture?include=documents', (string) $history[1]['request']->getUri());
        self::assertSame('POST', $history[2]['request']->getMethod());
        self::assertSame($base . '?mark_sent=true', (string) $history[2]['request']->getUri());
        self::assertSame('name=SDK+fixture', (string) $history[2]['request']->getBody());
        self::assertSame('PUT', $history[3]['request']->getMethod());
        self::assertSame($base . '/fixture', (string) $history[3]['request']->getUri());
        self::assertSame('name=Updated', (string) $history[3]['request']->getBody());

        if ($property !== 'companies') {
            self::assertSame('fixture', $entity->model()['data']['id']);
            self::assertSame($base . '/create', (string) $history[4]['request']->getUri());
            self::assertSame('GET', $history[4]['request']->getMethod());
        }
    }

    public static function entities(): iterable
    {
        foreach ([
            'clients', 'invoices', 'products', 'quotes', 'payments', 'tax_rates',
            'expenses', 'recurring_invoices', 'credits', 'projects', 'tasks',
            'vendors', 'users', 'companies', 'expense_categories', 'group_settings',
            'subscriptions', 'purchase_orders', 'bank_transactions', 'bank_integrations', 'locations',
        ] as $property) {
            yield $property => [$property];
        }
    }

    public function testBulkActionsAndDownload(): void
    {
        $history = [];
        $stack = HandlerStack::create(new MockHandler([
            new Response(200, [], '{"data":[]}'),
            new Response(200, [], '{"data":[]}'),
            new Response(200, [], '{"data":[]}'),
            new Response(200, [], '%PDF-fixture'),
        ]));
        $stack->push(Middleware::history($history));
        $sdk = (new InvoiceNinja('fixture'))->setOptions(['handler' => $stack]);

        self::assertSame(['data' => []], $sdk->clients->archive(['fixture']));
        self::assertSame(['data' => []], $sdk->clients->restore(['fixture']));
        self::assertSame(['data' => []], $sdk->clients->delete(['fixture']));
        self::assertSame('%PDF-fixture', $sdk->invoices->download(['fixture'], ['include' => 'documents']));

        foreach (['archive', 'restore', 'delete', 'download'] as $index => $action) {
            parse_str((string) $history[$index]['request']->getBody(), $body);
            self::assertSame($action, $body['action']);
            self::assertSame(['fixture'], $body['ids']);
            self::assertSame('POST', $history[$index]['request']->getMethod());
        }

        self::assertSame('/api/v1/invoices/bulk', $history[3]['request']->getUri()->getPath());
        self::assertSame('include=documents', $history[3]['request']->getUri()->getQuery());
        self::assertSame('0', $body['stream']);
    }

    #[DataProvider('staticKeys')]
    public function testStaticCollections(string $key): void
    {
        $sdk = (new InvoiceNinja('fixture'))->setOptions([
            'handler' => HandlerStack::create(new MockHandler([
                new Response(200, [], json_encode([$key => [['id' => 1, 'name' => 'Fixture']]])),
            ])),
        ]);

        self::assertSame([['id' => 1, 'name' => 'Fixture']], $sdk->statics->{$key}());
    }

    public static function staticKeys(): iterable
    {
        foreach (['currencies', 'industries', 'languages', 'countries', 'banks', 'payment_types', 'templates'] as $key) {
            yield $key => [$key];
        }
    }

    public function testMissingStaticCollection(): void
    {
        $sdk = (new InvoiceNinja('fixture'))->setOptions([
            'handler' => HandlerStack::create(new MockHandler([new Response(200, [], '{}')])),
        ]);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Static data not found');
        $sdk->statics->currencies();
    }

    public function testInvalidStaticJsonResponse(): void
    {
        $sdk = (new InvoiceNinja('fixture'))->setOptions([
            'handler' => HandlerStack::create(new MockHandler([new Response(200, [], 'null')])),
        ]);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Expected a JSON object or list.');
        $sdk->statics->currencies();
    }
    #[DataProvider('typedEntityCalls')]
    public function testTypedEndpointsApplyTheTransportContract(string $entity, string $method, array $arguments): void
    {
        foreach ([
            [200, '[{"id":"fixture"}]', [['id' => 'fixture']]],
            [204, '', []],
            [200, 'not json', null],
            [200, 'null', null],
            [200, 'true', null],
            [200, '', null],
            [302, '{}', null],
            [401, '{"message":"Unauthenticated"}', null],
        ] as [$status, $body, $expected]) {
            $sdk = (new InvoiceNinja('fixture'))->setOptions([
                'handler' => HandlerStack::create(new MockHandler([new Response($status, [], $body)])),
                'http_errors' => false,
            ]);

            if ($expected !== null) {
                self::assertSame($expected, $sdk->{$entity}->{$method}(...$arguments));
                continue;
            }

            try {
                $sdk->{$entity}->{$method}(...$arguments);
                self::fail('An incompatible response must fail before an endpoint returns.');
            } catch (ApiException $exception) {
                self::assertSame($status, $exception->getCode());
            }
        }
    }

    public static function typedEntityCalls(): iterable
    {
        foreach (['clients', 'companies'] as $entity) {
            yield "$entity all" => [$entity, 'all', []];
            yield "$entity get" => [$entity, 'get', ['fixture']];
            yield "$entity create" => [$entity, 'create', [['name' => 'fixture']]];
            yield "$entity update" => [$entity, 'update', ['fixture', ['name' => 'fixture']]];
        }
        yield 'model' => ['clients', 'model', []];
        yield 'bulk' => ['clients', 'bulk', ['archive', ['fixture']]];
        yield 'archive' => ['clients', 'archive', [['fixture']]];
        yield 'restore' => ['clients', 'restore', [['fixture']]];
        yield 'delete' => ['clients', 'delete', [['fixture']]];
    }

    #[DataProvider('staticKeys')]
    public function testStaticCollectionsRejectIncompatibleValuesAndAcceptEmptyContainers(string $key): void
    {
        foreach (['null', 'true', 'false', '42', '1.5', '"text"', '[]', '{}'] as $value) {
            $sdk = (new InvoiceNinja('fixture'))->setOptions([
                'handler' => HandlerStack::create(new MockHandler([new Response(200, [], '{"' . $key . '":' . $value . '}')])),
            ]);

            if ($value === '[]' || $value === '{}') {
                self::assertSame([], $sdk->statics->{$key}());
                continue;
            }

            try {
                $sdk->statics->{$key}();
                self::fail('A static collection must be an array.');
            } catch (ApiException $exception) {
                self::assertSame("Static data collection '{$key}' must be an array.", $exception->getMessage());
            }
        }
    }

    public function testBulkDownloadIsRejectedBeforeSendingAnyRequest(): void
    {
        $history = [];
        $mock = new MockHandler([new Response(200, [], '%PDF-unused')]);
        $stack = HandlerStack::create($mock);
        $stack->push(Middleware::history($history));
        $sdk = (new InvoiceNinja('fixture'))->setOptions(['handler' => $stack]);

        try {
            $sdk->invoices->bulk('download', ['fixture']);
            self::fail('Use the binary download helper instead.');
        } catch (ApiException $exception) {
            self::assertSame('Use download() for binary downloads.', $exception->getMessage());
        }

        self::assertCount(0, $history);
        self::assertCount(1, $mock);
    }
}
