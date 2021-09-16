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

class TasksTest extends TestCase
{
    protected string $token = "company-token-test";
    protected string $url = "https://ninja.test";

    public function testTasks()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $taks = $ninja->tasks->all();

        $this->assertTrue(is_array($taks));
        
    } 

    public function testTaskGet()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);
        $task = $ninja->tasks->create(['name' => 'Project', 'client_id' => $client['data']['id']]);

        $taks = $ninja->tasks->get($task['data']['id']);

        $this->assertTrue(is_array($taks));
        
    } 


    public function testTaskPut()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);
        $task = $ninja->tasks->create(['name' => 'Project', 'client_id' => $client['data']['id']]);

        $taks = $ninja->tasks->update($task['data']['id'], ['name' => 'Project 2']);
        
        $this->assertTrue(is_array($taks));
        
    } 


    public function testTaskPost()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);
        $taks = $ninja->tasks->create(['name' => 'Project', 'client_id' => $client['data']['id']]);        
        $this->assertTrue(is_array($taks));
        
    } 
}