<?php
/**
 * Invoice Ninja (https://invoiceninja.com).
 *
 * @link https://github.com/invoiceninja/sdk-php source repository
 *
 * @copyright Copyright (c) 2022. Invoice Ninja LLC (https://invoiceninja.com)
 *
 * @license https://opensource.org/licenses/MIT
 */

namespace InvoiceNinja\Sdk\Tests\Invoice;

use InvoiceNinja\Sdk\InvoiceNinja;
use InvoiceNinja\Sdk\Models\Client;
use InvoiceNinja\Sdk\Models\Invoice;
use InvoiceNinja\Sdk\Models\InvoiceItem;
use PHPUnit\Framework\TestCase;

class CreateInvoiceTest extends TestCase
{
    protected string $token = "company-token-test";
    protected string $url = "http://ninja.test:8000";

    public function setUp() :void
    {
        parent::setUp();
        $this->faker = \Faker\Factory::create();
    }

    // $ninja = new InvoiceNinja($this->token);
    // $ninja->setUrl($this->url);

    public function testInitInvoice()
    {
        $invoice = new Invoice();
        $this->assertInstanceOf(Invoice::class, $invoice);
    } 

    public function testInitItem()
    {
        $item = new InvoiceItem();
        $this->assertInstanceOf(InvoiceItem::class, $item);
    } 

    public function testBaseName()
    {
        $client = new Client;

        $this->assertEquals("InvoiceNinja\Sdk\Models\Client", get_class($client));
    }

    public function testClientValidation()
    {
         $client = new Client;
         $client->name = 'Jim';
         
         $client->save();    

         $this->assertInstanceOf(Client::class, $client);    
    }

    // public function testBuildInvoice()
    // {
    //     $client = new Client;
    //     $client->name = 'Jim';
    //     $client->save();

    //     $invoice = new Invoice;
    //     $invoice->setClient($client);
    // }
}