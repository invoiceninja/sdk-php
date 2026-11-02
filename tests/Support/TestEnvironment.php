<?php

namespace InvoiceNinja\Sdk\Tests\Support;

final class TestEnvironment
{
    /** @return array{url: string, token: string} */
    public static function connection(): array
    {
        $read = static fn (string $name) => $_ENV[$name] ?? $_SERVER[$name] ?? getenv($name);
        $url = $read('INVOICE_NINJA_TEST_URL') ?: 'http://ninja.test:8000';
        $token = $read('INVOICE_NINJA_TEST_TOKEN');
        $parts = is_string($url) ? parse_url($url) : false;

        if (!is_string($url) || !filter_var($url, FILTER_VALIDATE_URL) || !$parts
            || !in_array($parts['scheme'] ?? '', ['http', 'https'], true)
            || isset($parts['user']) || isset($parts['pass']) || isset($parts['query']) || isset($parts['fragment'])
            || !is_string($token) || trim($token) === '' || preg_match('/[\r\n]/', $token)) {
            throw new \RuntimeException('Set a valid HTTP(S) INVOICE_NINJA_TEST_URL and INVOICE_NINJA_TEST_TOKEN in .env or the environment for a disposable API instance.');
        }

        return ['url' => rtrim($url, '/'), 'token' => $token];
    }
}
