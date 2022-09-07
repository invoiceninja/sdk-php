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

class ExpenseCategoriesTest extends TestCase
{
    protected string $token = "company-token-test";
    protected string $url = "http://ninja.test:8000";


    public function setUp() :void
    {
        parent::setUp();
        $this->faker = \Faker\Factory::create();
    
    }

    public function testExpenseCategoriesPost()
    {
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $expense_categories = $ninja->expense_categories->create(['name' => $this->faker->text(10)]);
        
        $this->assertTrue(is_array($expense_categories));
    
        $expense_categories = $ninja->expense_categories->all();

        $this->assertTrue(is_array($expense_categories));
        
    } 

    public function testExpenseCategoryGet()
    {
    
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $expense_categories = $ninja->expense_categories->create(['name' => $this->faker->text(10)]);
        
        $this->assertTrue(is_array($expense_categories));

        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $expense_categories = $ninja->expense_categories->get($expense_categories['data']['id']);

        $this->assertTrue(is_array($expense_categories));
        
    } 


    public function testExpenseCategoryPut()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $expense_category = $this->faker->text(10);
        
        $expense_categories = $ninja->expense_categories->create(['name' => $expense_category]);

        $expense_categories = $ninja->expense_categories->update($expense_categories['data']['id'], ['name' => $this->faker->text(10)]);
        
        $this->assertTrue(is_array($expense_categories));
        
    } 


    public function testExpenseCategoryPost()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $expense_categories = $ninja->expense_categories->create(['name' => $this->faker->firstName]);
        
        $this->assertTrue(is_array($expense_categories));
        
    } 
}