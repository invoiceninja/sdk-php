<?php
/**
 * Product Ninja (https://invoiceninja.com).
 *
 * @link https://github.com/invoiceninja/sdk-php source repository
 *
 * @copyright Copyright (c) 2021. Product Ninja LLC (https://invoiceninja.com)
 *
 * @license https://opensource.org/licenses/MIT
 */

namespace InvoiceNinja\Sdk\Endpoints;

use GuzzleHttp\Exception\GuzzleException;
use InvoiceNinja\Sdk\InvoiceNinja;

class TaxRates
{

    protected InvoiceNinja $ninja;

    public function __construct(InvoiceNinja $ninja)
    {
        $this->ninja = $ninja;
    }

    /**
     * @param array $search 
     * @return void 
     * @throws GuzzleException 
     */
    public function all(array $search = [])
    {
        $query = ['query' => $search];

        return $this->ninja->send("GET", "/api/v1/tax_rates", $query);
    }

    public function get(string $tax_rate_id, array $search = [])
    {
        $query = ['query' => $search];

        return $this->ninja->send("GET", "/api/v1/tax_rates/{$tax_rate_id}", $query);
    }

    public function update(string $tax_rate_id, array $tax_rate)
    {
        $query = ['form_params' => $tax_rate];

        return $this->ninja->send("PUT", "/api/v1/tax_rates/{$tax_rate_id}", $query);
    }

    public function create(array $tax_rate, array $includes = [])
    {
        $query = ['form_params' => $tax_rate, 'query' => $includes];

        return $this->ninja->send("POST", "/api/v1/tax_rates", $query);
    }
}

