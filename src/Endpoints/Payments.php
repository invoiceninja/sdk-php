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

class Payments
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

        return $this->ninja->send("GET", "/api/v1/payments", $query);
    }

    public function get(string $payment_id, array $search = [])
    {
        $query = ['query' => $search];

        return $this->ninja->send("GET", "/api/v1/payments/{$payment_id}", $query);
    }

    public function update(string $payment_id, array $payment)
    {
        $query = ['form_params' => $payment];

        return $this->ninja->send("PUT", "/api/v1/payments/{$payment_id}", $query);
    }

    public function create(array $payment, array $includes = [])
    {
        $query = ['form_params' => $payment, 'query' => $includes];

        return $this->ninja->send("POST", "/api/v1/payments", $query);
    }
}

