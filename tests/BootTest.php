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

class BootTest extends TestCase
{
    /** @test */
    public function testInstanceInitiatization()
    {

        $ninja = new InvoiceNinja("test");

        $this->assertInstanceOf(InvoiceNinja::class, $ninja);

    }
}