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

class PaymentsTest extends TestCase
{
    protected string $token = "company-token-test";
    protected string $url = "http://ninja.test:8000";

    public function testPayments()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $payments = $ninja->payments->all();

        $this->assertTrue(is_array($payments));
        
    } 

    public function testInvoiceGet()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $payments = $ninja->payments->get('VolejRejNm');

        $this->assertTrue(is_array($payments));
        
    } 


    public function testInvoicePut()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $payments = $ninja->payments->update('VolejRejNm', ['transaction_reference' => 'ref']);
        
        $this->assertTrue(is_array($payments));
        
    } 


    public function testInvoicePost()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $payments = $ninja->payments->create(['client_id' => '7LDdwRb1YK', 'amount' => 10]);
        
        $this->assertTrue(is_array($payments));
        
    } 
}