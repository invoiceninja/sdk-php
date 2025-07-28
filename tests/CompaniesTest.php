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

class CompaniesTest extends TestCase
{
    public function testCompanies()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $companies = $ninja->companies->all();

        $this->assertTrue(is_array($companies));
        
    } 

    public function testCompanyGet()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $company = $ninja->companies->create([], ['include' => 'company,company.tokens']);
        $token = $company['data'][0]['company']['tokens'][0]['token'];
        $ninja->setToken($token);

        $companies = $ninja->companies->get($company['data'][0]['company']['id']);

        $this->assertTrue(is_array($companies));
        
    } 


    public function testCompanyPut()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $company = $ninja->companies->create([], ['include' => 'company,company.tokens']);
        $token = $company['data'][0]['company']['tokens'][0]['token'];
        $ninja->setToken($token);
        
        $companies = $ninja->companies->update($company['data'][0]['company']['id'], ['industry_id' => '1']);
        
        $this->assertTrue(is_array($companies));
        
    } 


    public function testCompanyPost()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $companies = $ninja->companies->create([], ['include' => 'company']);

        $this->assertTrue(is_array($companies));
        
    } 
}