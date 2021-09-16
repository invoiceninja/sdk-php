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

class Payments extends BaseEntity
{

    protected InvoiceNinja $ninja;

    protected string $uri = "/api/v1/payments";

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

        return $this->ninja->send("GET", "{$this->uri}", $query);
    }

    public function get(string $payment_id, array $search = [])
    {
        $query = ['query' => $search];

        return $this->ninja->send("GET", "{$this->uri}/{$payment_id}", $query);
    }

    public function update(string $payment_id, array $payment)
    {
        $query = ['form_params' => $payment];

        return $this->ninja->send("PUT", "{$this->uri}/{$payment_id}", $query);
    }

    public function create(array $payment, array $includes = [])
    {
        $query = ['form_params' => $payment, 'query' => $includes];

        return $this->ninja->send("POST", "{$this->uri}", $query);
    }
}

