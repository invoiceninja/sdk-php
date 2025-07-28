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

class ProductsTest extends TestCase
{
    public function testProducts()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $products = $ninja->products->all();

        $this->assertTrue(is_array($products));
        
    } 

    public function testInvoiceGet()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $product = $ninja->products->create(['product_key' => 'that']);

        $products = $ninja->products->get($product['data']['id']);

        $this->assertTrue(is_array($products));
        
    } 


    public function testInvoicePut()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $product = $ninja->products->create(['product_key' => 'thatx']);

        $products = $ninja->products->update($product['data']['id'], ['product_key' => 'this']);
        
        $this->assertTrue(is_array($products));
        
    } 


    public function testInvoicePost()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $products = $ninja->products->create(['product_key' => 'that']);
        
        $this->assertTrue(is_array($products));
        
    } 
}