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

class InvoicesTest extends TestCase
{
    protected string $token = "company-token-test";
    protected string $url = "http://ninja.test:8000";

    public function testInvoices()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $invoices = $ninja->invoices->all();

        $this->assertTrue(is_array($invoices));
        
    } 

    public function testInvoiceGet()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $invoices = $ninja->invoices->get('4w9aAOdvMR');

        $this->assertTrue(is_array($invoices));
        
    } 


    public function testInvoicePut()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $invoices = $ninja->invoices->update('4w9aAOdvMR', ['discount' => '10']);
        
        $this->assertTrue(is_array($invoices));
        
    } 


    public function testInvoicePost()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $invoices = $ninja->invoices->create(['client_id' => '7LDdwRb1YK']);
        
        $this->assertTrue(is_array($invoices));
        
    } 
}