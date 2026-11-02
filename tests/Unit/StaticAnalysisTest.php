<?php

namespace InvoiceNinja\Sdk\Tests\Unit;

use PHPUnit\Framework\TestCase;

class StaticAnalysisTest extends TestCase
{
    public function testPhpStanLevelSixPassesOnSource(): void
    {
        $root = dirname(__DIR__, 2);
        $command = escapeshellarg(PHP_BINARY).' '
            .escapeshellarg($root.'/vendor/bin/phpstan')
            .' analyse --configuration='.escapeshellarg($root.'/phpstan.neon')
            .' --memory-limit=512M --no-progress 2>&1';

        exec($command, $output, $exitCode);

        self::assertSame(0, $exitCode, implode("\n", $output));
    }
}
