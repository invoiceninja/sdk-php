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

class BulkActionsTest extends TestCase
{
    public function testArchive()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $client = $ninja->clients->create(['name' => 'A name']);

        $this->assertTrue(is_array($client));

        $this->assertEquals(0, $client['data']['archived_at']);

        $a[] = $client['data']['id'];

        $archived_client = $ninja->clients->bulk("archive", $a);

        $this->assertGreaterThan(0, $archived_client['data'][0]['archived_at']);

        $archived_client = $ninja->clients->bulk("restore", $a);

        $this->assertEquals(0, $client['data']['archived_at']);

        $archived_client = $ninja->clients->bulk("delete", $a);

        $this->assertGreaterThan(0, $archived_client['data'][0]['archived_at']);
        $this->assertEquals(1, $archived_client['data'][0]['is_deleted']);

    } 




}