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

class SubscriptionsTest extends TestCase
{
    protected string $token = "company-token-test";
    protected string $url = "http://ninja.test:8000";



    public function setUp() :void
    {
        parent::setUp();
        $this->faker = \Faker\Factory::create();
    }

    public function testSubscriptions()
    {
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $subscriptions = $ninja->subscriptions->create(['name' => $this->faker->firstName]);
        
        $this->assertTrue(is_array($subscriptions));
        
    } 

    public function testSubscriptionGet()
    {
    
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $subscription = $ninja->subscriptions->create(['name' => $this->faker->firstName]);
        
        $this->assertTrue(is_array($subscription));

        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $subscriptions = $ninja->subscriptions->get($subscription['data']['id']);

        $this->assertTrue(is_array($subscriptions));
        
    } 


    public function testSubscriptionPut()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $subscription = $ninja->subscriptions->create(['name' => $this->faker->firstName]);

        $subscriptions = $ninja->subscriptions->update($subscription['data']['id'], ['name' => $this->faker->firstName]);
        
        $this->assertTrue(is_array($subscriptions));
        
    } 


    public function testSubscriptionPost()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $subscriptions = $ninja->subscriptions->create(['name' => $this->faker->firstName]);
        
        $this->assertTrue(is_array($subscriptions));
        
    } 
}