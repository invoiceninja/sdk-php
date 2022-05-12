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

use GuzzleHttp\Exception\GuzzleException;
use InvoiceNinja\Sdk\InvoiceNinja;

class BaseEntity
{

    public function bulk(string $action, array $ids)
    {
        $query['form_params'] = ["action" => $action, "ids" => $ids];

        return $this->ninja->send("POST", "{$this->uri}/bulk", $query);
    }

    public function archive(array $entity_array)
    {
        return $this->bulk("archive", $entity_array);
    }

    public function delete(array $entity_array)
    {
        return $this->bulk("delete", $entity_array);
    }

    public function restore(array $entity_array)
    {
        return $this->bulk("restore", $entity_array);
    }

    public function all(array $search = [])
    {
        $query = ['query' => $search];

        return $this->ninja->send("GET", "{$this->uri}", $query);
    }

    public function get(string $entity_id, array $search = [])
    {
        $query = ['query' => $search];

        return $this->ninja->send("GET", "{$this->uri}/{$entity_id}", $query);
    }

    public function update(string $entity_id, array $entity)
    {
        $query = ['form_params' => $entity];

        return $this->ninja->send("PUT", "{$this->uri}/{$entity_id}", $query);
    }

    public function create(array $entity, array $includes = [])
    {
        $query = ['form_params' => $entity, 'query' => $includes];

        return $this->ninja->send("POST", "{$this->uri}", $query);
    }

}

