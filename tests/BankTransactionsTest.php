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

class BankTransactionsTest extends TestCase
{
    protected string $token = "company-token-test";
    protected string $url = "http://ninja.test:8000";



    public function setUp() :void
    {
        parent::setUp();
        $this->faker = \Faker\Factory::create();
    }

    public function testBankTransactions()
    {
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $bank_integrations = $ninja->bank_integrations->create(['bank_account_name' => $this->faker->firstName]);

        $transactions = $ninja->bank_transactions->create(['bank_integration_id' => $bank_integrations['data']['id'], 'name' => $this->faker->firstName]);
        
        $this->assertTrue(is_array($transactions));
        
    } 

    public function testTransactionGet()
    {
    
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $bank_integrations = $ninja->bank_integrations->create(['bank_account_name' => $this->faker->firstName]);

        $transaction = $ninja->bank_transactions->create(['bank_integration_id' => $bank_integrations['data']['id'],'name' => $this->faker->firstName]);
        
        $this->assertTrue(is_array($transaction));

        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $transactions = $ninja->bank_transactions->get($transaction['data']['id']);

        $this->assertTrue(is_array($transactions));
        
    } 


    public function testTransactionPut()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $bank_integrations = $ninja->bank_integrations->create(['bank_account_name' => $this->faker->firstName]);

        $transaction = $ninja->bank_transactions->create(['bank_integration_id' => $bank_integrations['data']['id'], 'name' => $this->faker->firstName]);

        $transactions = $ninja->bank_transactions->update($transaction['data']['id'], ['bank_integration_id' => $bank_integrations['data']['id'], 'name' => $this->faker->firstName, 'date' => '2022-10-10', 'amount' => 100]);
        
        $this->assertTrue(is_array($transactions));
        
    } 


    public function testTransactionPost()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $bank_integrations = $ninja->bank_integrations->create(['bank_account_name' => $this->faker->firstName]);

        $transactions = $ninja->bank_transactions->create(['bank_integration_id' => $bank_integrations['data']['id'], 'name' => $this->faker->firstName]);
        
        $this->assertTrue(is_array($transactions));
        
    } 
}