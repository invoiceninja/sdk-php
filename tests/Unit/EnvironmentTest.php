<?php

namespace InvoiceNinja\Sdk\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class EnvironmentTest extends TestCase
{
    #[DataProvider('environments')]
    public function testBootstrapLoadsDotenvAndPreservesOverrides(?string $contents, array $overrides, array $expected): void
    {
        $directory = sys_get_temp_dir() . '/sdk-php-env-' . bin2hex(random_bytes(8));
        mkdir($directory . '/tests', 0700, true);
        symlink(dirname(__DIR__, 2) . '/vendor', $directory . '/vendor');
        copy(dirname(__DIR__) . '/bootstrap.php', $directory . '/tests/bootstrap.php');

        if ($contents !== null) {
            file_put_contents($directory . '/.env', $contents);
        }

        $environment = getenv();
        unset($environment['INVOICE_NINJA_TEST_URL'], $environment['INVOICE_NINJA_TEST_TOKEN']);
        $environment = array_merge($environment, $overrides);
        $script = 'require ' . var_export($directory . '/tests/bootstrap.php', true) . ';'
            . 'echo json_encode(['
            . '$_ENV["INVOICE_NINJA_TEST_URL"] ?? $_SERVER["INVOICE_NINJA_TEST_URL"] ?? null,'
            . '$_ENV["INVOICE_NINJA_TEST_TOKEN"] ?? $_SERVER["INVOICE_NINJA_TEST_TOKEN"] ?? null]);';

        try {
            $process = proc_open([PHP_BINARY, '-r', $script], [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, sys_get_temp_dir(), $environment);
            self::assertIsResource($process);
            $output = stream_get_contents($pipes[1]);
            $errors = stream_get_contents($pipes[2]);
            fclose($pipes[1]);
            fclose($pipes[2]);

            self::assertSame(0, proc_close($process), $errors);
            self::assertSame($expected, json_decode($output, true, 512, JSON_THROW_ON_ERROR));
        } finally {
            if (is_file($directory . '/.env')) {
                unlink($directory . '/.env');
            }
            unlink($directory . '/tests/bootstrap.php');
            unlink($directory . '/vendor');
            rmdir($directory . '/tests');
            rmdir($directory);
        }
    }

    public static function environments(): iterable
    {
        $contents = "# Test credentials\nINVOICE_NINJA_TEST_URL=\"http://example.test:9000\"\nINVOICE_NINJA_TEST_TOKEN='fixture-token'\n";
        yield 'quoted file values from another working directory' => [$contents, [], ['http://example.test:9000', 'fixture-token']];
        yield 'exported variables override file values' => [$contents, [
            'INVOICE_NINJA_TEST_URL' => 'http://override.test:9001',
            'INVOICE_NINJA_TEST_TOKEN' => 'override-token',
        ], ['http://override.test:9001', 'override-token']];
        yield 'missing file is allowed for offline tests' => [null, [], [null, null]];
    }
}
