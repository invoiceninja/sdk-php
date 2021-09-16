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

class Clients extends BaseEntity
{

    protected InvoiceNinja $ninja;

    protected string $uri = "/api/v1/clients";

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

    public function get(string $client_id, array $search = [])
    {
        $query = ['query' => $search];

        return $this->ninja->send("GET", "{$this->uri}/{$client_id}", $query);
    }

    public function update(string $client_id, array $client)
    {
        $query = ['form_params' => $client];

        return $this->ninja->send("PUT", "{$this->uri}/{$client_id}", $query);
    }

    public function create(array $client, array $includes = [])
    {
        $query = ['form_params' => $client, 'query' => $includes];

        return $this->ninja->send("POST", "{$this->uri}", $query);
    }
}

