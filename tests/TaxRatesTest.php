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

class TaxRatesTest extends TestCase
{
    protected string $token = "company-token-test";
    protected string $url = "http://ninja.test:8000";

    public function testProducts()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $tax_rates = $ninja->tax_rates->all();

        $this->assertTrue(is_array($tax_rates));
        
    } 

    public function testTaxRateGet()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $tax_rates = $ninja->tax_rates->get('Opnel5aKBz');

        $this->assertTrue(is_array($tax_rates));
        
    } 


    public function testTaxRatePut()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $tax_rates = $ninja->tax_rates->update('Opnel5aKBz', ['rate' => 10, 'name' => 'GST']);
        
        $this->assertTrue(is_array($tax_rates));
        
    } 


    public function testTaxRatePost()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $tax_rates = $ninja->tax_rates->create(['rate' => 10, 'name' => 'GSTX']);
        
        $this->assertTrue(is_array($tax_rates));
        
    } 
}