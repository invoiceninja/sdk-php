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

class TaxRatesTest extends IntegrationTestCase
{
    public function testProducts()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $tax_rates = $ninja->tax_rates->all();

        $this->assertTrue(is_array($tax_rates));
        
    } 

    public function testTaxRateGet()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $tax_rate = $ninja->tax_rates->create(['rate' => 10, 'name' => $this->faker->uuid()]);

        $tax_rates = $ninja->tax_rates->get($tax_rate['data']['id']);

        $this->assertTrue(is_array($tax_rates));
        
    } 


    public function testTaxRatePut()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $tax_rate = $ninja->tax_rates->create(['rate' => 10, 'name' => $this->faker->uuid()]);

        $tax_rates = $ninja->tax_rates->update($tax_rate['data']['id'], ['rate' => 10, 'name' => $this->faker->uuid()]);
        
        $this->assertTrue(is_array($tax_rates));
        
    } 


    public function testTaxRatePost()
    {
        
        $ninja = new InvoiceNinja($this->token);
        $ninja->setUrl($this->url);

        $tax_rates = $ninja->tax_rates->create(['rate' => 10, 'name' => $this->faker->uuid()]);
        
        $this->assertTrue(is_array($tax_rates));
        
    } 
}
