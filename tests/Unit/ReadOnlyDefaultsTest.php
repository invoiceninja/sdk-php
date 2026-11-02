<?php

namespace InvoiceNinja\Sdk\Tests\Unit;

use PHPUnit\Framework\TestCase;

class ReadOnlyDefaultsTest extends TestCase
{
    public function testDefaultPhpunitRunExcludesWriteIntegrationTests(): void
    {
        $root = dirname(__DIR__, 2);
        $process = proc_open([PHP_BINARY, $root . '/vendor/bin/phpunit', '--list-tests'], [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, $root);
        self::assertIsResource($process);
        $output = stream_get_contents($pipes[1]) . stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        self::assertSame(0, proc_close($process), $output);
        self::assertStringContainsString('TransportTest::', $output);
        self::assertStringNotContainsString('CompaniesTest::', $output);
        self::assertStringNotContainsString('ClientsTest::', $output);
        self::assertStringNotContainsString('WebhooksTest::', $output);
    }

    public function testWriteIntegrationGateRejectsMissingAuthorization(): void
    {
        $root = dirname(__DIR__, 2);
        $environment = array_merge(getenv(), ['INVOICE_NINJA_ALLOW_WRITE_TESTS' => '0']);
        $process = proc_open([PHP_BINARY, $root . '/tests/check-integration-opt-in.php'], [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, $root, $environment);
        self::assertIsResource($process);
        $output = stream_get_contents($pipes[1]) . stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        self::assertSame(1, proc_close($process));
        self::assertStringContainsString('Write integration tests are disabled', $output);
    }
}
