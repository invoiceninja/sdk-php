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

class PaymentsTest extends TestCase
{
    public function testPayments()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $payments = $ninja->payments->all();

        $this->assertTrue(is_array($payments));
        
    } 

    public function testInvoiceGet()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);
        $payment = $ninja->payments->create(['client_id' => $client['data']['id'], 'amount' => 10]);

        $payments = $ninja->payments->get($payment['data']['id']);

        $this->assertTrue(is_array($payments));
        
    } 


    public function testInvoicePut()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);
        $payment = $ninja->payments->create(['client_id' => $client['data']['id'], 'amount' => 10]);

        $payments = $ninja->payments->update($payment['data']['id'], ['transaction_reference' => 'ref']);
        
        $this->assertTrue(is_array($payments));
        
    } 


    public function testInvoicePost()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);

        $payments = $ninja->payments->create(['client_id' => $client['data']['id'], 'amount' => 10]);
        
        $this->assertTrue(is_array($payments));
        
    } 

    public function testInvoicePostPaymentWithInvoicePayment()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

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

        $invoice = $ninja->invoices->create($invoice, ['mark_sent' => true]);

        $payments = $ninja->payments->create([
            'client_id' => $client['data']['id'], 
            'amount' => 10,
            'invoices' => [
                [
                'invoice_id' => $invoice['data']['id'],
                'amount' => 10
                ],
            ],
        ]);
        
        $this->assertTrue(is_array($payments));
        
    } 

    public function testInvoicePostPaymentWithInvoicePaymentAndTransactionReference()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

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

        $invoice = $ninja->invoices->create($invoice, ['mark_sent' => true]);

        $payments = $ninja->payments->create([
            'client_id' => $client['data']['id'], 
            'amount' => 10,
            'invoices' => [
                [
                'invoice_id' => $invoice['data']['id'],
                'amount' => 10
                ],
            ],
            'transaction_reference' => 'IAMAWESOME'
        ]);
        
        $this->assertTrue(is_array($payments));

        $this->assertEquals('IAMAWESOME', $payments['data']['transaction_reference']);

        
    } 

}