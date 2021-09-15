<?php

namespace InvoiceNinja\Sdk\Tests;

use InvoiceNinja\Sdk\Client;
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

    public function testClients()
    {
        $ninja = new InvoiceNinja("company-token-test");
        $ninja->setUrl("http://ninja.test:8000");

        $clients = $ninja->clients->all(['balance' => '0:gt']);

        $this->assertTrue(is_array($clients));
        
// print_r($clients);
        // die(var_dump($clients));

        // die($clients);
    } 



}