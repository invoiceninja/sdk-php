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
use Psr\Http\Message\StreamInterface;
use InvoiceNinja\Sdk\Exceptions\ApiException;

class BaseEntity
{

    protected InvoiceNinja $ninja;

    protected string $uri;

    /**
     * @param list<string> $ids
     *
     * @return array<array-key, mixed>
     */
    public function bulk(string $action, array $ids): array
    {
        if ($action === 'download') {
            throw new ApiException('Use download() for binary downloads.');
        }

        $query['form_params'] = ["action" => $action, "ids" => $ids];

        return $this->ninja->send("POST", "{$this->uri}/bulk", $query);
    }

    /**
     * @param list<string> $entity_array
     *
     * @return array<array-key, mixed>
     */
    public function archive(array $entity_array): array
    {
        return $this->bulk("archive", $entity_array);
    }

    /**
     * @param list<string> $entity_array
     *
     * @return array<array-key, mixed>
     */
    public function delete(array $entity_array): array
    {
        return $this->bulk("delete", $entity_array);
    }

    /**
     * @param list<string> $entity_array
     *
     * @return array<array-key, mixed>
     */
    public function restore(array $entity_array): array
    {
        return $this->bulk("restore", $entity_array);
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
     * @return array<array-key, mixed>
     */
    public function model(): array
    {
        return $this->ninja->send("GET", "{$this->uri}/create");
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

    /**
     * @param list<string> $entity
     * @param array<string, mixed> $includes
     */
    public function download(array $entity, array $includes = []): string
    {
        $data = ['ids' => $entity, 'action' => 'download', 'stream' => false];

        $query = ['form_params' => $data, 'query' => $includes];

        return $this->ninja->stream("POST", "{$this->uri}/bulk", $query);
    }

    /**
     * @param list<string> $entity
     * @param array<string, mixed> $includes
     */
    public function downloadAsStream(array $entity, array $includes = []): StreamInterface
    {
        $data = ['ids' => $entity, 'action' => 'download', 'stream' => false];

        $query = ['form_params' => $data, 'query' => $includes];

        return $this->ninja->streamAsObject("POST", "{$this->uri}/bulk", $query);
    }
}
