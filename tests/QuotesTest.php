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

class QuotesTest extends TestCase
{
    protected string $token = "company-token-test";
    protected string $url = "http://ninja.test:8000";

    public function testQuotes()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $quotes = $ninja->quotes->all();

        $this->assertTrue(is_array($quotes));
        
    } 

    public function testQuoteGet()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $quotes = $ninja->quotes->get('gl9avmeG1v');

        $this->assertTrue(is_array($quotes));
        
    } 


    public function testQuotePut()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $quotes = $ninja->quotes->update('gl9avmeG1v', ['discount' => '10']);
        
        $this->assertTrue(is_array($quotes));
        
    } 


    public function testQuotePost()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $quotes = $ninja->quotes->create(['client_id' => '7LDdwRb1YK']);
        
        $this->assertTrue(is_array($quotes));
        
    } 
}