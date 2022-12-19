<?php
/**
 * PurchaseOrder Ninja (https://invoiceninja.com).
 *
 * @link https://github.com/invoiceninja/sdk-php source repository
 *
 * @copyright Copyright (c) 2022. PurchaseOrder Ninja LLC (https://invoiceninja.com)
 *
 * @license https://opensource.org/licenses/MIT
 */

namespace InvoiceNinja\Sdk\Tests;

use InvoiceNinja\Sdk\InvoiceNinja;
use PHPUnit\Framework\TestCase;

class PurchaseOrdersTest extends TestCase
{
    protected string $token = "company-token-test";
    protected string $url = "http://ninja.test:8000";

    public function testPurchaseOrders()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->vendors->create(['name' => 'Brand spanking new client']);

        $invoice = $ninja->purchase_orders->create(['vendor_id' => $client['data']['id']]);

        $purchase_orders = $ninja->purchase_orders->all();

        $this->assertTrue(is_array($purchase_orders));
        
    } 

    public function testPurchaseOrderGet()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->vendors->create(['name' => 'Brand spanking new client']);

        $invoice = $ninja->purchase_orders->create(['vendor_id' => $client['data']['id']]);

        $purchase_orders = $ninja->purchase_orders->get($invoice['data']['id']);

        $this->assertTrue(is_array($purchase_orders));
        
    } 


    public function testPurchaseOrderPut()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->vendors->create(['name' => 'Brand spanking new client']);

        $invoice = $ninja->purchase_orders->create(['vendor_id' => $client['data']['id']]);

        $purchase_orders = $ninja->purchase_orders->update($invoice['data']['id'], ['discount' => '10']);
        
        $this->assertTrue(is_array($purchase_orders));
        
    } 


    public function testPurchaseOrderPost()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->vendors->create(['name' => 'Brand spanking new client']);

        $purchase_orders = $ninja->purchase_orders->create(['vendor_id' => $client['data']['id']]);
        
        $this->assertTrue(is_array($purchase_orders));
        
    } 


    public function testPurchaseOrderPostWithItems()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->vendors->create(['name' => 'Brand spanking new client']);

        $invoice = [
            'vendor_id' => $client['data']['id'],
            'line_items' => [
                [
                    'product_key' => 'test',
                    'notes' => 'description',
                    'quantity' => 1,
                    'cost' => 10
                ]
            ],
        ];

        $invoice = $ninja->purchase_orders->create($invoice);
        
        $this->assertTrue(is_array($invoice));
        $this->assertEquals(10, $invoice['data']['amount']);
        

    } 

    public function testPurchaseOrderPostWithMultiItems()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->vendors->create(['name' => 'Brand spanking new client']);

        $invoice = [
            'vendor_id' => $client['data']['id'],
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

        $invoice = $ninja->purchase_orders->create($invoice);
        
        $this->assertTrue(is_array($invoice));
        $this->assertEquals(20, $invoice['data']['amount']);
        

    } 

    public function testPurchaseOrderPostWithMultiItemsMarkSent()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->vendors->create(['name' => 'Brand spanking new client']);

        $invoice = [
            'vendor_id' => $client['data']['id'],
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

        $invoice = $ninja->purchase_orders->create($invoice, ['mark_sent' => "true"]);
        
        $this->assertTrue(is_array($invoice));

        $this->assertEquals(20, $invoice['data']['amount']);
        $this->assertEquals(20, $invoice['data']['balance']);

    } 

    public function testDownloadPurchaseOrder()
    {

        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->vendors->create(['name' => 'Brand spanking new client']);

        $invoice = [
            'vendor_id' => $client['data']['id'],
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

        $invoice = $ninja->purchase_orders->create($invoice, ['mark_sent' => "true"]);

        $download = $ninja->purchase_orders->download([$invoice['data']['id']]);

        $this->assertNotNull($download);

    }

}