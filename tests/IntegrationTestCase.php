<?php

namespace InvoiceNinja\Sdk\Tests;

use Faker\Factory;
use Faker\Generator;
use InvoiceNinja\Sdk\Tests\Support\TestEnvironment;
use PHPUnit\Framework\TestCase;

abstract class IntegrationTestCase extends TestCase
{
    protected string $token;
    protected string $url;
    protected Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();

        $allow_writes = $_ENV['INVOICE_NINJA_ALLOW_WRITE_TESTS'] ?? $_SERVER['INVOICE_NINJA_ALLOW_WRITE_TESTS'] ?? getenv('INVOICE_NINJA_ALLOW_WRITE_TESTS');
        if ($allow_writes !== '1') {
            throw new \RuntimeException('Write integration tests are disabled. Explicit opt-in is required for a disposable API.');
        }

        $connection = TestEnvironment::connection();
        $this->url = $connection['url'];
        $this->token = $connection['token'];
        $this->faker = Factory::create();
    }
}
