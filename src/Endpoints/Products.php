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

class Products extends BaseEntity
{

    protected InvoiceNinja $ninja;

    protected string $uri = "/api/v1/products";

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

    public function get(string $product_id, array $search = [])
    {
        $query = ['query' => $search];

        return $this->ninja->send("GET", "{$this->uri}/{$product_id}", $query);
    }

    public function update(string $product_id, array $product)
    {
        $query = ['form_params' => $product];

        return $this->ninja->send("PUT", "{$this->uri}/{$product_id}", $query);
    }

    public function create(array $product, array $includes = [])
    {
        $query = ['form_params' => $product, 'query' => $includes];

        return $this->ninja->send("POST", "{$this->uri}", $query);
    }
}

