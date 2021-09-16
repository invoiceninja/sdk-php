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

class ExpensesTest extends TestCase
{
    protected string $token = "company-token-test";
    protected string $url = "https://ninja.test";

    public function testExpenses()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $expenses = $ninja->expenses->all();

        $this->assertTrue(is_array($expenses));
        
    } 

    public function testExpenseGet()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->clients->create(['name' => 'Brand spanking new clients']);
        $expense = $ninja->expenses->create(['client_id' => $client['data']['id']]);

        $expenses = $ninja->expenses->get($expense['data']['id']);

        $this->assertTrue(is_array($expenses));
        
    } 


    public function testExpensePut()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);


        $clients = $ninja->clients->create(['name' => 'Brand spanking new client']);
        $expense = $ninja->expenses->create(['client_id' => $clients['data']['id']]);

        $expenses = $ninja->expenses->update($expense['data']['id'], ['amount' => '10']);
        
        $this->assertTrue(is_array($expenses));
        
    } 


    public function testExpensePost()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);
        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);

        $expenses = $ninja->expenses->create(['client_id' => $client['data']['id']]);
        
        $this->assertTrue(is_array($expenses));
        
    } 
}