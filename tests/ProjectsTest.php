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

class ProjectsTest extends TestCase
{
    protected string $token = "company-token-test";
    protected string $url = "https://ninja.test";

    public function testProjects()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $projects = $ninja->projects->all();

        $this->assertTrue(is_array($projects));
        
    } 

    public function testProjectGet()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);
        $product = $ninja->projects->create(['name' => 'Project', 'client_id' => $client['data']['id']]);

        $projects = $ninja->projects->get($product['data']['id']);

        $this->assertTrue(is_array($projects));
        
    } 


    public function testProjectPut()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);
        $product = $ninja->projects->create(['name' => 'Project', 'client_id' => $client['data']['id']]);

        $projects = $ninja->projects->update($product['data']['id'], ['name' => 'Project 2']);
        
        $this->assertTrue(is_array($projects));
        
    } 


    public function testProjectPost()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);
        $projects = $ninja->projects->create(['name' => 'Project', 'client_id' => $client['data']['id']]);        
        $this->assertTrue(is_array($projects));
        
    } 
}