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

class LocationsTest extends IntegrationTestCase
{

    public function testLocations()
    {
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);

        $locations = $ninja->locations->create(['name' => (string)microtime(true), 'client_id' => $client['data']['id']]);

        $this->assertTrue(is_array($locations));

        $locations = $ninja->locations->all(['balance' => '0:gt']);

        $this->assertTrue(is_array($locations));

    }

    public function testLocationGet()
    {

        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);

        $location = $ninja->locations->create(['name' => (string)microtime(true), 'client_id' => $client['data']['id']]);

        $this->assertTrue(is_array($location));

        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $locations = $ninja->locations->get($location['data']['id']);

        $this->assertTrue(is_array($locations));

    }


    public function testLocationPut()
    {

        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);

        $location = $ninja->locations->create(['name' => (string)microtime(true), 'client_id' => $client['data']['id']]);

        $locations = $ninja->locations->update($location['data']['id'], ['name' => (string)microtime(true), 'client_id' => $client['data']['id']]);

        $this->assertTrue(is_array($locations));

    }

    public function testLocationPost()
    {

        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $client = $ninja->clients->create(['name' => 'Brand spanking new client']);

        $locations = $ninja->locations->create(['name' => (string)microtime(true), 'client_id' => $client['data']['id']]);

        $this->assertTrue(is_array($locations));

    }

}
