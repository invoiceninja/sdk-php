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

namespace InvoiceNinja\Sdk\Tests;

use InvoiceNinja\Sdk\InvoiceNinja;
use PHPUnit\Framework\TestCase;

class InvoicesTest extends TestCase
{
    protected string $token = "company-token-test";
    protected string $url = "http://ninja.test:8000";

    public function testInvoices()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);

        $invoice = $ninja->invoices->create(['client_id' => $client['data']['id']]);

        $invoices = $ninja->invoices->all();

        $this->assertTrue(is_array($invoices));
        
    } 

    public function testInvoiceGet()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);

        $invoice = $ninja->invoices->create(['client_id' => $client['data']['id']]);

        $invoices = $ninja->invoices->get($invoice['data']['id']);

        $this->assertTrue(is_array($invoices));
        
    } 


    public function testInvoicePut()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);

        $invoice = $ninja->invoices->create(['client_id' => $client['data']['id']]);

        $invoices = $ninja->invoices->update($invoice['data']['id'], ['discount' => '10']);
        
        $this->assertTrue(is_array($invoices));
        
    } 


    public function testInvoicePost()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);

        $invoices = $ninja->invoices->create(['client_id' => $client['data']['id']]);
        
        $this->assertTrue(is_array($invoices));
        
    } 


    public function testInvoicePostWithItems()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);

        $invoice = [
            'client_id' => $client['data']['id'],
            'line_items' => [
                [
                    'product_key' => 'test',
                    'notes' => 'description',
                    'quantity' => 1,
                    'cost' => 10
                ]
            ],
        ];

        $invoice = $ninja->invoices->create($invoice);
        
        $this->assertTrue(is_array($invoice));
        $this->assertEquals(10, $invoice['data']['amount']);
        

    } 

    public function testInvoicePostWithMultiItems()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);

        $invoice = [
            'client_id' => $client['data']['id'],
            'line_items' => [
                [
                    'product_key' => 'test',
                    'notes' => 'description',
                    'quantity' => 1,
                    'cost' => 10
                ],
                [                
                    'product_key' => 'test',
                    'notes' => 'description',
                    'quantity' => 1,
                    'cost' => 10
                ],
            ],
        ];

        $invoice = $ninja->invoices->create($invoice);
        
        $this->assertTrue(is_array($invoice));
        $this->assertEquals(20, $invoice['data']['amount']);
        

    } 

    public function testInvoicePostWithMultiItemsMarkSent()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);

        $invoice = [
            'client_id' => $client['data']['id'],
            'line_items' => [
                [
                    'product_key' => 'test',
                    'notes' => 'description',
                    'quantity' => 1,
                    'cost' => 10
                ],
                [                
                    'product_key' => 'test',
                    'notes' => 'description',
                    'quantity' => 1,
                    'cost' => 10
                ],
            ],
        ];

        $invoice = $ninja->invoices->create($invoice, ['mark_sent' => "true"]);
        
        $this->assertTrue(is_array($invoice));
        $this->assertEquals(20, $invoice['data']['amount']);
        
        $this->assertEquals(20, $invoice['data']['balance']);

    } 

    public function testDownloadInvoice()
    {

        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);

        $invoice = [
            'client_id' => $client['data']['id'],
            'line_items' => [
                [
                    'product_key' => 'test',
                    'notes' => 'description',
                    'quantity' => 1,
                    'cost' => 10
                ],
                [                
                    'product_key' => 'test',
                    'notes' => 'description',
                    'quantity' => 1,
                    'cost' => 10
                ],
            ],
        ];

        $invoice = $ninja->invoices->create($invoice, ['mark_sent' => "true"]);

        $download = $ninja->invoices->download([$invoice['data']['id']]);

        $this->assertNotNull($download);

    }

}