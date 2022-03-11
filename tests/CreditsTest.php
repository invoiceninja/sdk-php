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

class CreditsTest extends TestCase
{
    protected string $token = "company-token-test";
    protected string $url = "http://ninja.test:8000";

    public function testCredits()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);

        $credit = $ninja->credits->create(['client_id' => $client['data']['id']]);

        $credits = $ninja->credits->all();

        $this->assertTrue(is_array($credits));
        
    } 

    public function testCreditGet()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);

        $credit = $ninja->credits->create(['client_id' => $client['data']['id']]);

        $credits = $ninja->credits->get($credit['data']['id']);

        $this->assertTrue(is_array($credits));
        
    } 


    public function testCreditPut()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);

        $credit = $ninja->credits->create(['client_id' => $client['data']['id']]);

        $credits = $ninja->credits->update($credit['data']['id'], ['discount' => '10']);
        
        $this->assertTrue(is_array($credits));
        
    } 


    public function testCreditPost()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);

        $credits = $ninja->credits->create(['client_id' => $client['data']['id']]);
        
        $this->assertTrue(is_array($credits));
        
    } 
}