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

class CompaniesTest extends IntegrationTestCase
{

    public function testCompanies()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $companies = $ninja->companies->all();

        $this->assertTrue(is_array($companies));
        
    } 

    public function testCompanyGet()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $company = $ninja->companies->create([], ['include' => 'company,token']);
        $token = $company['data'][0]['token']['token'];
        $this->assertNotEmpty($token);
        $ninja->setToken($token);

        $companies = $ninja->companies->get($company['data'][0]['company']['id']);

        $this->assertTrue(is_array($companies));
        
    } 


    public function testCompanyPut()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $company = $ninja->companies->create([], ['include' => 'company,token']);
        $token = $company['data'][0]['token']['token'];
        $this->assertNotEmpty($token);
        $ninja->setToken($token);
        
        $companies = $ninja->companies->update($company['data'][0]['company']['id'], ['industry_id' => '1']);
        
        $this->assertTrue(is_array($companies));
        
    } 


    public function testCompanyPost()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $companies = $ninja->companies->create([], ['include' => 'company']);

        $this->assertTrue(is_array($companies));
        
    } 
}
