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

class QuotesTest extends TestCase
{



    public function testQuotes()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $quotes = $ninja->quotes->all();

        $this->assertTrue(is_array($quotes));
        
    } 

    public function testQuoteGet()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);
        $quote = $ninja->quotes->create(['client_id' => $client['data']['id']]);

        $quotes = $ninja->quotes->get($quote['data']['id']);

        $this->assertTrue(is_array($quotes));
        
    } 


    public function testQuotePut()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);


        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);
        $quote = $ninja->quotes->create(['client_id' => $client['data']['id']]);

        $quotes = $ninja->quotes->update($quote['data']['id'], ['discount' => '10']);
        
        $this->assertTrue(is_array($quotes));
        
    } 


    public function testQuotePost()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);
        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);

        $quotes = $ninja->quotes->create(['client_id' => $client['data']['id']]);
        
        $this->assertTrue(is_array($quotes));
        
    } 
}