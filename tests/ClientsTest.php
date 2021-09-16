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

class ClientsTest extends TestCase
{
    protected string $token = "company-token-test";
    protected string $url = "http://ninja.test:8000";

    public function testClients()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $clients = $ninja->clients->all(['balance' => '0:gt']);

        $this->assertTrue(is_array($clients));
        
    } 

    public function testClientGet()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $clients = $ninja->clients->get('7LDdwRb1YK');

        $this->assertTrue(is_array($clients));
        
    } 


    public function testClientPut()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $clients = $ninja->clients->update('7LDdwRb1YK', ['name' => 'A new client name updated']);
        
        $this->assertTrue(is_array($clients));
        
    } 


    public function testClientPost()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $clients = $ninja->clients->create(['name' => 'Brand spanking new client']);
        
        $this->assertTrue(is_array($clients));
        
    } 
}