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

class ProjectsTest extends TestCase
{
    public function testProjects()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $projects = $ninja->projects->all();

        $this->assertTrue(is_array($projects));
        
    } 

    public function testProjectGet()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);
        $product = $ninja->projects->create(['name' => 'Project', 'client_id' => $client['data']['id']]);

        $projects = $ninja->projects->get($product['data']['id']);

        $this->assertTrue(is_array($projects));
        
    } 


    public function testProjectPut()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);
        $product = $ninja->projects->create(['name' => 'Project', 'client_id' => $client['data']['id']]);

        $projects = $ninja->projects->update($product['data']['id'], ['name' => 'Project 2']);
        
        $this->assertTrue(is_array($projects));
        
    } 


    public function testProjectPost()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);
        $projects = $ninja->projects->create(['name' => 'Project', 'client_id' => $client['data']['id']]);        
        $this->assertTrue(is_array($projects));
        
    } 
}