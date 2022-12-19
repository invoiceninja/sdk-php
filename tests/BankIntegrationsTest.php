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

class BankIntegrationsTest extends TestCase
{
    protected string $token = "company-token-test";
    protected string $url = "http://ninja.test:8000";



    public function setUp() :void
    {
        parent::setUp();
        $this->faker = \Faker\Factory::create();
    }

    public function testBankIntegrations()
    {
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $bank_integrations = $ninja->bank_integrations->create(['bank_account_name' => $this->faker->firstName]);
        
        $this->assertTrue(is_array($bank_integrations));
        
    } 

    public function testBankIntegrationGet()
    {
    
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $subscription = $ninja->bank_integrations->create(['bank_account_name' => $this->faker->firstName]);
        
        $this->assertTrue(is_array($subscription));

        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $bank_integrations = $ninja->bank_integrations->get($subscription['data']['id']);

        $this->assertTrue(is_array($bank_integrations));
        
    } 


    public function testBankIntegrationPut()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $subscription = $ninja->bank_integrations->create(['bank_account_name' => $this->faker->firstName]);

        $bank_integrations = $ninja->bank_integrations->update($subscription['data']['id'], ['bank_account_name' => $this->faker->firstName]);
        
        $this->assertTrue(is_array($bank_integrations));
        
    } 


    public function testBankIntegrationPost()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $bank_integrations = $ninja->bank_integrations->create(['bank_account_name' => $this->faker->firstName]);
        
        $this->assertTrue(is_array($bank_integrations));
        
    } 
}