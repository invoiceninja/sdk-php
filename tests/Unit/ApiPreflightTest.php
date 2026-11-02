<?php

namespace InvoiceNinja\Sdk\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ApiPreflightTest extends TestCase
{
    #[DataProvider('responses')]
    public function testPreflightReportsAuthenticationWithoutLeakingCredentials(int $status, string $body, int $expected_exit, string $message, array $headers = []): void
    {
        $socket = stream_socket_server('tcp://127.0.0.1:0', $error_code, $error_message);
        self::assertIsResource($socket);
        $address = stream_socket_get_name($socket, false);
        fclose($socket);
        $router = tempnam(sys_get_temp_dir(), 'sdk-api-preflight-');
        $response_headers = '';
        foreach ($headers as $name => $value) {
            $response_headers .= 'header(' . var_export($name . ': ' . $value, true) . ');';
        }
        file_put_contents($router, '<?php http_response_code(' . $status . ');' . $response_headers
            . 'header("Content-Type: application/json");'
            . 'if (($_SERVER["HTTP_X_API_TOKEN"] ?? "") !== "preflight-fixture-secret") { http_response_code(401); }'
            . 'echo ' . var_export($body, true) . ';');
        $server = proc_open([PHP_BINARY, '-S', $address, $router], [1 => ['file', '/dev/null', 'a'], 2 => ['file', '/dev/null', 'a']], $server_pipes);
        self::assertIsResource($server);

        try {
            $ready = false;
            for ($attempt = 0; $attempt < 100; $attempt++) {
                $probe = @stream_socket_client('tcp://' . $address, $error_code, $error_message, 0.05);
                if ($probe !== false) {
                    fclose($probe);
                    $ready = true;
                    break;
                }
                usleep(10000);
            }
            self::assertTrue($ready, 'Loopback fixture server did not start.');

            $environment = array_merge(getenv(), [
                'INVOICE_NINJA_TEST_URL' => 'http://' . $address,
                'INVOICE_NINJA_TEST_TOKEN' => 'preflight-fixture-secret',
            ]);
            $process = proc_open([PHP_BINARY, dirname(__DIR__) . '/check-api.php'], [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, null, $environment);
            self::assertIsResource($process);
            $output = stream_get_contents($pipes[1]) . stream_get_contents($pipes[2]);
            fclose($pipes[1]);
            fclose($pipes[2]);

            self::assertSame($expected_exit, proc_close($process), $output);
            self::assertStringContainsString($message, $output);
            self::assertStringNotContainsString('preflight-fixture-secret', $output);
        } finally {
            proc_terminate($server);
            proc_close($server);
            unlink($router);
        }
    }

    public static function responses(): iterable
    {
        yield 'authenticated' => [200, '{"company_name":"Fixture","user_name":"Fixture"}', 0, 'API preflight passed'];
        yield 'unprovisioned token' => [403, '{"message":"Invalid token"}', 1, 'authentication was rejected'];
        yield 'unauthenticated' => [401, '{"message":"Unauthenticated"}', 1, 'authentication was rejected'];
        yield 'server failure' => [500, '{"message":"Server error"}', 1, 'HTTP 500'];
        yield 'reflected credential is not printed' => [500, '{"message":"preflight-fixture-secret"}', 1, 'HTTP 500'];
        yield 'wrong endpoint' => [200, '{}', 1, 'expected the authenticated Invoice Ninja ping endpoint'];
        yield 'malformed response' => [200, '<html>Not the API</html>', 1, 'expected the authenticated Invoice Ninja ping endpoint'];
        yield 'null response' => [200, 'null', 1, 'expected the authenticated Invoice Ninja ping endpoint'];
        yield 'null identity field' => [200, '{"company_name":null,"user_name":"Fixture"}', 1, 'expected the authenticated Invoice Ninja ping endpoint'];
        yield 'redirect is not followed' => [302, '{}', 1, 'HTTP 302', ['Location' => 'http://127.0.0.1:9/must-not-be-requested']];
    }
}
