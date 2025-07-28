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

class StaticsTest extends TestCase
{

    public function testGetCurrencies()
    {
        
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $currencies = $ninja->statics->currencies();

        $this->assertTrue(is_array($currencies));
        
    } 

    
}