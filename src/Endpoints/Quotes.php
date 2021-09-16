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

class Quotes
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

        return $this->ninja->send("GET", "/api/v1/quotes", $query);
    }

    public function get(string $quote_id, array $search = [])
    {
        $query = ['query' => $search];

        return $this->ninja->send("GET", "/api/v1/quotes/{$quote_id}", $query);
    }

    public function update(string $quote_id, array $quote)
    {
        $query = ['form_params' => $quote];

        return $this->ninja->send("PUT", "/api/v1/quotes/{$quote_id}", $query);
    }

    public function create(array $entity, array $includes = [])
    {
        $query = ['form_params' => $entity, 'query' => $includes];

        return $this->ninja->send("POST", "/api/v1/quotes", $query);
    }
}

