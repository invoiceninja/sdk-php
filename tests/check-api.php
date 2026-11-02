<?php

require __DIR__ . '/bootstrap.php';

use GuzzleHttp\Client;
use InvoiceNinja\Sdk\Tests\Support\TestEnvironment;

try {
    $connection = TestEnvironment::connection();
    $client = new Client([
        'connect_timeout' => 3,
        'timeout' => 10,
        'allow_redirects' => false,
        'http_errors' => false,
        'headers' => [
            'X-API-TOKEN' => $connection['token'],
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json',
        ],
    ]);
    $response = $client->get($connection['url'] . '/api/v1/ping');
    $status = $response->getStatusCode();

    if (in_array($status, [401, 403], true)) {
        fwrite(STDERR, "API preflight failed (HTTP {$status}): authentication was rejected.\n"
            . "Check INVOICE_NINJA_TEST_URL and INVOICE_NINJA_TEST_TOKEN. No test data was created.\n");
        exit(1);
    }

    $body = json_decode((string) $response->getBody(), true);
    if ($status !== 200 || !is_array($body) || !isset($body['company_name'], $body['user_name'])) {
        fwrite(STDERR, "API preflight failed: expected the authenticated Invoice Ninja ping endpoint (HTTP {$status}).\n");
        exit(1);
    }

    echo "API preflight passed.\n";
} catch (Throwable $exception) {
    // Transport exceptions can contain request details; never print credentials here.
    fwrite(STDERR, "API preflight failed: check the test URL, credentials, TLS settings, and API availability.\n");
    exit(1);
}
