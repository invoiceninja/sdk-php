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

class VendorsTest extends TestCase
{
    public function testVendors()
    {
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $vendors = $ninja->vendors->create(['name' => 'Brand spanking new vendor']);
        
        $this->assertTrue(is_array($vendors));
    
        $vendors = $ninja->vendors->all(['balance' => '0:gt']);

        $this->assertTrue(is_array($vendors));
        
    } 

    public function testVendorGet()
    {
    
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $vendor = $ninja->vendors->create(['name' => 'Brand spanking new vendor']);
        
        $this->assertTrue(is_array($vendor));

        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $vendors = $ninja->vendors->get($vendor['data']['id']);

        $this->assertTrue(is_array($vendors));
        
    } 


    public function testVendorPut()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $vendor = $ninja->vendors->create(['name' => 'Brand spanking new vendor']);

        $vendors = $ninja->vendors->update($vendor['data']['id'], ['name' => 'A new vendor name updated']);
        
        $this->assertTrue(is_array($vendors));
        
    } 


    public function testVendorPost()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $vendors = $ninja->vendors->create(['name' => 'Brand spanking new vendor']);
        
        $this->assertTrue(is_array($vendors));
        
    } 
}