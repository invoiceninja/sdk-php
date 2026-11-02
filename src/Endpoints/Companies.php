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

namespace InvoiceNinja\Sdk\Endpoints;

use InvoiceNinja\Sdk\InvoiceNinja;

class Companies 
{

    protected InvoiceNinja $ninja;

    protected string $uri = "/api/v1/companies";

    public function __construct(InvoiceNinja $ninja)
    {
        $this->ninja = $ninja;
    }

    /**
     * @param array<string, mixed> $search
     *
     * @return array<array-key, mixed>
     */
    public function all(array $search = []): array
    {
        $query = ['query' => $search];

        return $this->ninja->send("GET", "{$this->uri}", $query);
    }

    /**
     * @param array<string, mixed> $search
     *
     * @return array<array-key, mixed>
     */
    public function get(string $entity_id, array $search = []): array
    {
        $query = ['query' => $search];

        return $this->ninja->send("GET", "{$this->uri}/{$entity_id}", $query);
    }

    /**
     * @param array<string, mixed> $entity
     *
     * @return array<array-key, mixed>
     */
    public function update(string $entity_id, array $entity): array
    {
        $query = ['form_params' => $entity];

        return $this->ninja->send("PUT", "{$this->uri}/{$entity_id}", $query);
    }

    /**
     * @param array<string, mixed> $entity
     * @param array<string, mixed> $includes
     *
     * @return array<array-key, mixed>
     */
    public function create(array $entity, array $includes = []): array
    {
        $query = ['form_params' => $entity, 'query' => $includes];

        return $this->ninja->send("POST", "{$this->uri}", $query);
    }
}
