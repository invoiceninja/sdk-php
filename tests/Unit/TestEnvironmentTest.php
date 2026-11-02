<?php

namespace InvoiceNinja\Sdk\Tests\Unit;

use InvoiceNinja\Sdk\Tests\Support\TestEnvironment;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class TestEnvironmentTest extends TestCase
{
    #[DataProvider('invalidConnections')]
    public function testInvalidConfigurationFailsBeforeAnyHttpRequest(mixed $url, mixed $token): void
    {
        $original_environment = $_ENV;
        $original_server = $_SERVER;
        $_ENV['INVOICE_NINJA_TEST_URL'] = $url;
        $_ENV['INVOICE_NINJA_TEST_TOKEN'] = $token;

        try {
            $this->expectException(\RuntimeException::class);
            $this->expectExceptionMessage('Set a valid HTTP(S)');
            TestEnvironment::connection();
        } finally {
            $_ENV = $original_environment;
            $_SERVER = $original_server;
        }
    }

    public static function invalidConnections(): iterable
    {
        yield 'malformed URL' => ['not-a-url', 'fixture'];
        yield 'FTP' => ['ftp://example.test', 'fixture'];
        yield 'file URL' => ['file:///tmp/fixture', 'fixture'];
        yield 'embedded username' => ['https://user@example.test', 'fixture'];
        yield 'embedded password' => ['https://user:password@example.test', 'fixture'];
        yield 'query' => ['https://example.test?token=fixture', 'fixture'];
        yield 'fragment' => ['https://example.test#fragment', 'fixture'];
        yield 'empty token' => ['http://127.0.0.1:9', ''];
        yield 'blank token' => ['http://127.0.0.1:9', '   '];
        yield 'header injection' => ['http://127.0.0.1:9', "fixture\r\nX-Injected: value"];
        yield 'non-string token' => ['http://127.0.0.1:9', ['fixture']];
    }

    #[DataProvider('validConnections')]
    public function testValidConfigurationIsNormalized(string $url, string $expected_url): void
    {
        $original_environment = $_ENV;
        $original_server = $_SERVER;
        $_ENV['INVOICE_NINJA_TEST_URL'] = $url;
        $_ENV['INVOICE_NINJA_TEST_TOKEN'] = 'fixture';

        try {
            self::assertSame(['url' => $expected_url, 'token' => 'fixture'], TestEnvironment::connection());
        } finally {
            $_ENV = $original_environment;
            $_SERVER = $original_server;
        }
    }

    public static function validConnections(): iterable
    {
        yield 'HTTPS with trailing slash' => ['https://example.test/', 'https://example.test'];
        yield 'local HTTP with port' => ['http://127.0.0.1:9000', 'http://127.0.0.1:9000'];
        yield 'reverse proxy base path' => ['https://example.test/invoices/', 'https://example.test/invoices'];
    }
}
