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

class VendorsTest extends TestCase
{
    protected string $token = "company-token-test";
    protected string $url = "https://ninja.test";

    public function testVendors()
    {
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $vendors = $ninja->vendors->create(['name' => 'Brand spanking new vendor']);
        
        $this->assertTrue(is_array($vendors));
    
        $vendors = $ninja->vendors->all(['balance' => '0:gt']);

        $this->assertTrue(is_array($vendors));
        
    } 

    public function testVendorGet()
    {
    
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $vendor = $ninja->vendors->create(['name' => 'Brand spanking new vendor']);
        
        $this->assertTrue(is_array($vendor));

        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $vendors = $ninja->vendors->get($vendor['data']['id']);

        $this->assertTrue(is_array($vendors));
        
    } 


    public function testVendorPut()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $vendor = $ninja->vendors->create(['name' => 'Brand spanking new vendor']);

        $vendors = $ninja->vendors->update($vendor['data']['id'], ['name' => 'A new vendor name updated']);
        
        $this->assertTrue(is_array($vendors));
        
    } 


    public function testVendorPost()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $vendors = $ninja->vendors->create(['name' => 'Brand spanking new vendor']);
        
        $this->assertTrue(is_array($vendors));
        
    } 
}