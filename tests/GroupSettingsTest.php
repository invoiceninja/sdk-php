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

class GroupSettingsTest extends IntegrationTestCase
{
    public function testAddGroupSetting()
    {
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $settings = ['currency_id' => '1'];

        $group_setting = $ninja->group_settings->create(['name' => $this->faker->uuid(), 'settings' => $settings]);
        
        $this->assertTrue(is_array($group_setting));
    
        $group_settings = $ninja->group_settings->all();

        $this->assertTrue(is_array($group_settings));
        
    } 


    public function testUpdateGroupSetting()
    {
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $settings = ['currency_id' => '1'];

        $new_name = $this->faker->uuid();
        $group_setting = $ninja->group_settings->create(['name' => $new_name, 'settings' => $settings]);
        $this->assertTrue(is_array($group_setting));
    
        $newer_name = $this->faker->uuid();
        $settings = ['currency_id' => '2'];
        
        $updated_group_setting = $ninja->group_settings->update($group_setting['data']['id'], ['name' => $newer_name, 'settings' => $settings]);

        $this->assertEquals($newer_name, $updated_group_setting['data']['name']);
        $this->assertEquals('2', $updated_group_setting['data']['settings']['currency_id']);

    } 

    
}