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


    public function setUp() :void
    {
        parent::setUp();
        $this->faker = \Faker\Factory::create();
    }

    public function testSubscriptions()
    {
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $subscriptions = $ninja->subscriptions->create(['name' => $this->faker->firstName]);
        
        $this->assertTrue(is_array($subscriptions));
        
    } 

    public function testSubscriptionGet()
    {
    
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $subscription = $ninja->subscriptions->create(['name' => $this->faker->firstName]);
        
        $this->assertTrue(is_array($subscription));

        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $subscriptions = $ninja->subscriptions->get($subscription['data']['id']);

        $this->assertTrue(is_array($subscriptions));
        
    } 


    public function testSubscriptionPut()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $subscription = $ninja->subscriptions->create(['name' => $this->faker->firstName]);

        $subscriptions = $ninja->subscriptions->update($subscription['data']['id'], ['name' => $this->faker->firstName]);
        
        $this->assertTrue(is_array($subscriptions));
        
    } 


    public function testSubscriptionPost()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $subscriptions = $ninja->subscriptions->create(['name' => $this->faker->firstName]);
        
        $this->assertTrue(is_array($subscriptions));
        
    } 
}