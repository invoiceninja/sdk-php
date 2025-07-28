<?php
/**
 * PurchaseOrder Ninja (https://purchase_orderninja.com).
 *
 * @link https://github.com/purchase_orderninja/sdk-php source repository
 *
 * @copyright Copyright (c) 2022. PurchaseOrder Ninja LLC (https://purchase_orderninja.com)
 *
 * @license https://opensource.org/licenses/MIT
 */

namespace InvoiceNinja\Sdk\Tests;

use InvoiceNinja\Sdk\InvoiceNinja;
use PHPUnit\Framework\TestCase;

class PurchaseOrdersTest extends TestCase
{
    public function testPurchaseOrders()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $client = $ninja->vendors->create(['name' => 'Brand spanking new client']);

        $purchase_order = $ninja->purchase_orders->create(['vendor_id' => $client['data']['id']]);

        $purchase_orders = $ninja->purchase_orders->all();

        $this->assertTrue(is_array($purchase_orders));
        
    } 

    public function testPurchaseOrderGet()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $client = $ninja->vendors->create(['name' => 'Brand spanking new client']);

        $purchase_order = $ninja->purchase_orders->create(['vendor_id' => $client['data']['id']]);

        $purchase_orders = $ninja->purchase_orders->get($purchase_order['data']['id']);

        $this->assertTrue(is_array($purchase_orders));
        
    } 


    public function testPurchaseOrderPut()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $client = $ninja->vendors->create(['name' => 'Brand spanking new client']);

        $purchase_order = $ninja->purchase_orders->create(['vendor_id' => $client['data']['id']]);

        $purchase_orders = $ninja->purchase_orders->update($purchase_order['data']['id'], ['discount' => '10']);
        
        $this->assertTrue(is_array($purchase_orders));
        
    } 


    public function testPurchaseOrderPost()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $client = $ninja->vendors->create(['name' => 'Brand spanking new client']);

        $purchase_orders = $ninja->purchase_orders->create(['vendor_id' => $client['data']['id']]);
        
        $this->assertTrue(is_array($purchase_orders));
        
    } 


    public function testPurchaseOrderPostWithItems()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $client = $ninja->vendors->create(['name' => 'Brand spanking new client']);

        $purchase_order = [
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

        $purchase_order = $ninja->purchase_orders->create($purchase_order);
        
        $this->assertTrue(is_array($purchase_order));
        $this->assertEquals(10, $purchase_order['data']['amount']);
        

    } 

    public function testPurchaseOrderPostWithMultiItems()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $client = $ninja->vendors->create(['name' => 'Brand spanking new client']);

        $purchase_order = [
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

        $purchase_order = $ninja->purchase_orders->create($purchase_order);
        
        $this->assertTrue(is_array($purchase_order));
        $this->assertEquals(20, $purchase_order['data']['amount']);
        

    } 

    public function testPurchaseOrderPostWithMultiItemsMarkSent()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $client = $ninja->vendors->create(['name' => 'Brand spanking new client']);

        $purchase_order = [
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

        $purchase_order = $ninja->purchase_orders->create($purchase_order, ['mark_sent' => "true"]);
        
        $this->assertTrue(is_array($purchase_order));

        $this->assertEquals(20, $purchase_order['data']['amount']);
        $this->assertEquals(20, $purchase_order['data']['balance']);

    } 

    public function testDownloadPurchaseOrder()
    {

        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $client = $ninja->vendors->create(['name' => 'Brand spanking new client']);

        $purchase_order = [
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

        $purchase_order = $ninja->purchase_orders->create($purchase_order, ['mark_sent' => "true"]);

        $download = $ninja->purchase_orders->download([$purchase_order['data']['id']]);

        $this->assertNotNull($download);

    }

}