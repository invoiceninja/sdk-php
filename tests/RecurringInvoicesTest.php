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

class RecurringInvoicesTest extends TestCase
{
    public function testInvoices()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);

        $invoice = $ninja->recurring_invoices->create(['frequency_id'=>1,'client_id' => $client['data']['id']]);

        $invoices = $ninja->recurring_invoices->all();

        $this->assertTrue(is_array($invoices));
        
    } 

    public function testInvoiceGet()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);

        $invoice = $ninja->recurring_invoices->create(['frequency_id'=>1,'client_id' => $client['data']['id']]);

        $invoices = $ninja->recurring_invoices->get($invoice['data']['id']);

        $this->assertTrue(is_array($invoices));
        
    } 


    public function testInvoicePut()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);

        $invoice = $ninja->recurring_invoices->create(['frequency_id'=>1,'client_id' => $client['data']['id']]);

        $invoices = $ninja->recurring_invoices->update($invoice['data']['id'], ['discount' => '10']);
        
        $this->assertTrue(is_array($invoices));
        
    } 


    public function testInvoicePost()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);
        $invoices = $ninja->recurring_invoices->create(['frequency_id'=>1,'client_id' => $client['data']['id']]);
        
        $this->assertTrue(is_array($invoices));
        
    } 
}