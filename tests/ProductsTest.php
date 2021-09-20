<?php
/**
 * Invoice Ninja (https://invoiceninja.com).
 *
 * @link https://github.com/invoiceninja/sdk-php source repository
 *
 * @copyright Copyright (c) 2021. Invoice Ninja LLC (https://invoiceninja.com)
 *
 * @license https://opensource.org/licenses/MIT
 */

namespace InvoiceNinja\Sdk\Tests;

use InvoiceNinja\Sdk\InvoiceNinja;
use PHPUnit\Framework\TestCase;

class ProductsTest extends TestCase
{
    protected string $token = "company-token-test";
    protected string $url = "http://ninja.test:8000";

    public function testProducts()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $products = $ninja->products->all();

        $this->assertTrue(is_array($products));
        
    } 

    public function testInvoiceGet()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $product = $ninja->products->create(['product_key' => 'that']);

        $products = $ninja->products->get($product['data']['id']);

        $this->assertTrue(is_array($products));
        
    } 


    public function testInvoicePut()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $product = $ninja->products->create(['product_key' => 'thatx']);

        $products = $ninja->products->update($product['data']['id'], ['product_key' => 'this']);
        
        $this->assertTrue(is_array($products));
        
    } 


    public function testInvoicePost()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $products = $ninja->products->create(['product_key' => 'that']);
        
        $this->assertTrue(is_array($products));
        
    } 
}