<?php
/**
 * Invoice Ninja (https://invoiceninja.com).
 *
 * @link https://github.com/invoiceninja/sdk-php source repository
 *
 * @copyright Copyright (c) 2021. Invoice Ninja LLC (https://invoiceninja.com)
 *
 * @license https://opensource.org/licenses/MIT
 */

namespace InvoiceNinja\Sdk\Endpoints;

use GuzzleHttp\Exception\GuzzleException;
use InvoiceNinja\Sdk\InvoiceNinja;

class Invoices extends BaseEntity
{

    protected InvoiceNinja $ninja;

    protected string $uri = "/api/v1/invoices";

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

    public function get(string $invoice_id, array $search = [])
    {
        $query = ['query' => $search];

        return $this->ninja->send("GET", "{$this->uri}/{$invoice_id}", $query);
    }

    public function update(string $invoice_id, array $invoices)
    {
        $query = ['form_params' => $invoices];

        return $this->ninja->send("PUT", "{$this->uri}/{$invoice_id}", $query);
    }

    public function create(array $invoices, array $includes = [])
    {
        $query = ['form_params' => $invoices, 'query' => $includes];

        return $this->ninja->send("POST", "{$this->uri}", $query);
    }
}

