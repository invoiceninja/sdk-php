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
    protected string $url = "https://ninja.test";

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

        $invoices = $ninja->invoices->create(['client_id' => '7LDdwRb1YK']);
        
        $this->assertTrue(is_array($invoices));
        
    } 
}