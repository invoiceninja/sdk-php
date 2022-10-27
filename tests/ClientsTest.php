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

class ClientsTest extends TestCase
{
    protected string $token = "company-token-test";
    protected string $url = "http://ninja.test:8000";

    public function testClients()
    {
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $clients = $ninja->clients->create(['name' => 'Brand spanking new client']);
        
        $this->assertTrue(is_array($clients));
    
        $clients = $ninja->clients->all(['balance' => '0:gt']);

        $this->assertTrue(is_array($clients));
        
    } 

    public function testClientGet()
    {
    
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);
        
        $this->assertTrue(is_array($client));

        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $clients = $ninja->clients->get($client['data']['id']);

        $this->assertTrue(is_array($clients));
        
    } 


    public function testClientPut()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);

        $clients = $ninja->clients->update($client['data']['id'], ['name' => 'A new client name updated']);
        
        $this->assertTrue(is_array($clients));
        
    } 

    public function testClientUpdate()
    {


        $create =  [
            "id" => "Vyb8E85dvA",
            "user_id" => "Vyb82qgevA",
            "assigned_user_id" => "",
            "name" => "Bosco-Keeling",
            "website" => "https =>\/\/www.fahey.com\/aut-et-quasi-animi-quia-recusandae-aut",
            "private_notes" => "Consequatur ut voluptate et rerum natus excepturi qui. Aut consequatur ut aut. Sint iure magni possimus vel omnis non.",
            "balance" => 1537.73,
            "group_settings_id" => "",
            "paid_to_date" => 0,
            "credit_balance" => 0,
            "last_login" => 0,
            "size_id" => "",
            "public_notes" => "",
            "client_hash" => "qKaZ2Y0xgGnhXsHKyV8mKxXv6tF9tHx2h4AlmAul",
            "address1" => "52506",
            "address2" => "94067 Maverick Ranch Suite 080",
            "phone" => "",
            "city" => "Port Danaside",
            "state" => "Georgia",
            "postal_code" => "51342-6348",
            "country_id" => "686",
            "industry_id" => "",
            "custom_value1" => "",
            "custom_value2" => "",
            "custom_value3" => "",
            "custom_value4" => "",
            "shipping_address1" => "4149",
            "shipping_address2" => "71001 Pagac Lock",
            "shipping_city" => "Blickberg",
            "shipping_state" => "Vermont",
            "shipping_postal_code" => "83727-6609",
            "shipping_country_id" => "4",
            "settings" => [
                "entity" => "App\\Models\\Client",
                "industry_id" => "",
                "size_id" => "",
                "currency_id" => "1"
            ],
            "is_deleted" => false,
            "vat_number" => "441202649",
            "id_number" => "",
            "updated_at" => 1666836715,
            "archived_at" => 0,
            "created_at" => 1666836715,
            "display_name" => "Bosco-Keeling",
            "contacts" => [
                [
                    "id" => "xkazvJVyaJ",
                    "first_name" => "Julius",
                    "last_name" => "Bosco",
                    "email" => "user@example.com",
                    "created_at" => 1666836715,
                    "updated_at" => 1666836715,
                    "archived_at" => 0,
                    "is_primary" => true,
                    "is_locked" => false,
                    "phone" => "+1-567-788-1383",
                    "custom_value1" => "",
                    "custom_value2" => "",
                    "custom_value3" => "",
                    "custom_value4" => "",
                    "contact_key" => "wTu6GS0QPLY7liX0wWqQKl75OUrmtTpfZ9aHrURu",
                    "send_email" => true,
                    "last_login" => 0,
                    "password" => "**********",
                    "link" => "http =>\/\/ninja.test =>8000\/client\/key_login\/wTu6GS0QPLY7liX0wWqQKl75OUrmtTpfZ9aHrURu"
                ],
                [
                    "id" => "w9aANGjlbv",
                    "first_name" => "Carson",
                    "last_name" => "Frami",
                    "email" => "jean15@example.org",
                    "created_at" => 1666836715,
                    "updated_at" => 1666836715,
                    "archived_at" => 0,
                    "is_primary" => false,
                    "is_locked" => false,
                    "phone" => "+1-785-926-8599",
                    "custom_value1" => "",
                    "custom_value2" => "",
                    "custom_value3" => "",
                    "custom_value4" => "",
                    "contact_key" => "qk80shH9KmuWVwOo6Ca918gENLnEwlt7nbORzCRg",
                    "send_email" => true,
                    "last_login" => 0,
                    "password" => "**********",
                    "link" => "http =>\/\/ninja.test =>8000\/client\/key_login\/qk80shH9KmuWVwOo6Ca918gENLnEwlt7nbORzCRg"
                ],
                [
                    "id" => "46dBNJlYd7",
                    "first_name" => "Tavares",
                    "last_name" => "Ebert",
                    "email" => "stokes.eden@example.org",
                    "created_at" => 1666836715,
                    "updated_at" => 1666836715,
                    "archived_at" => 0,
                    "is_primary" => false,
                    "is_locked" => false,
                    "phone" => "+1-870-840-9344",
                    "custom_value1" => "",
                    "custom_value2" => "",
                    "custom_value3" => "",
                    "custom_value4" => "",
                    "contact_key" => "j7r0rh5L3WgeqHJGm7wa0GHnIqXKSfr1N6eHEchR",
                    "send_email" => true,
                    "last_login" => 0,
                    "password" => "**********",
                    "link" => "http =>\/\/ninja.test =>8000\/client\/key_login\/j7r0rh5L3WgeqHJGm7wa0GHnIqXKSfr1N6eHEchR"
                ]
            ]
        ];

        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->clients->create($create);

        $this->assertTrue(is_array($client));

        $clients = $ninja->clients->update($client['data']['id'], ['name' => 'A new client name updated']);

        $this->assertTrue(is_array($client));

    }





    public function testClientPost()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $clients = $ninja->clients->create(['name' => 'Brand spanking new client']);
        
        $this->assertTrue(is_array($clients));
        
    } 


    public function testClientWithContactPost()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $clients = $ninja->clients->create([
            'name' => 'Brand spanking new client',
            'contacts' => [
                [
                    'first_name' => 'first',
                    'last_name' => 'last',
                    'send_email' => true,
                    'email' => 'joe@gmail.com',
                ],

            ]
        ]);
        
        $this->assertTrue(is_array($clients));
        
    } 
}