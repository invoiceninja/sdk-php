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

class Products
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

        return $this->ninja->send("GET", "/api/v1/products", $query);
    }

    public function get(string $product_id, array $search = [])
    {
        $query = ['query' => $search];

        return $this->ninja->send("GET", "/api/v1/products/{$product_id}", $query);
    }

    public function update(string $product_id, array $product)
    {
        $query = ['form_params' => $product];

        return $this->ninja->send("PUT", "/api/v1/products/{$product_id}", $query);
    }

    public function create(array $product, array $includes = [])
    {
        $query = ['form_params' => $product, 'query' => $includes];

        return $this->ninja->send("POST", "/api/v1/products", $query);
    }
}

