<?php

namespace InvoiceNinja\Sdk\Tests;

use InvoiceNinja\Sdk\InvoiceNinja;
use PHPUnit\Framework\TestCase;

class BootTest extends TestCase
{
    /** @test */
    public function testInstanceInitiatization()
    {

        $ninja = new InvoiceNinja("test");

        $this->assertInstanceOf(InvoiceNinja::class, $ninja);

    }

}