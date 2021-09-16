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

class Invoices
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

        return $this->ninja->send("GET", "/api/v1/invoices", $query);
    }

    public function get(string $invoice_id, array $search = [])
    {
        $query = ['query' => $search];

        return $this->ninja->send("GET", "/api/v1/invoices/{$invoice_id}", $query);
    }

    public function update(string $invoice_id, array $invoice)
    {
        $query = ['form_params' => $invoice];

        return $this->ninja->send("PUT", "/api/v1/invoices/{$invoice_id}", $query);
    }

    public function create(array $invoice, array $includes = [])
    {
        $query = ['form_params' => $invoice, 'query' => $includes];

        return $this->ninja->send("POST", "/api/v1/invoices", $query);
    }
}

