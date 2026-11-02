<?php

require __DIR__ . '/bootstrap.php';

$allow_writes = $_ENV['INVOICE_NINJA_ALLOW_WRITE_TESTS'] ?? $_SERVER['INVOICE_NINJA_ALLOW_WRITE_TESTS'] ?? getenv('INVOICE_NINJA_ALLOW_WRITE_TESTS');
if ($allow_writes !== '1') {
    fwrite(STDERR, "Write integration tests are disabled. Set INVOICE_NINJA_ALLOW_WRITE_TESTS=1 only for an explicitly authorized disposable API.\n");
    exit(1);
}
