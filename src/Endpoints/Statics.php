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

use InvoiceNinja\Sdk\Exceptions\ApiException;
use InvoiceNinja\Sdk\InvoiceNinja;

class Statics
{

    protected InvoiceNinja $ninja;

    public function __construct(InvoiceNinja $ninja)
    {
        $this->ninja = $ninja;
    }

    /**
     * @param array<string, mixed> $search
     *
     * @return array<array-key, mixed>
     */
    private function get(array $search = []): array
    {
        return $this->ninja->send("GET", "/api/v1/statics", $search);
    }

    /**
     * @return array<array-key, mixed>
     */
    public function currencies(): array
    {
        return $this->getStaticByKey('currencies');
    }

    /**
     * @return array<array-key, mixed>
     */
    public function industries(): array
    {
        return $this->getStaticByKey('industries');
    }

    /**
     * @return array<array-key, mixed>
     */
    public function languages(): array
    {
        return $this->getStaticByKey('languages');
    }

    /**
     * @return array<array-key, mixed>
     */
    public function countries(): array
    {
        return $this->getStaticByKey('countries');
    }

    /**
     * @return array<array-key, mixed>
     */
    public function banks(): array
    {
        return $this->getStaticByKey('banks');
    }

    /**
     * @return array<array-key, mixed>
     */
    public function payment_types(): array
    {
        return $this->getStaticByKey('payment_types');
    }

    /**
     * @return array<array-key, mixed>
     */
    public function templates(): array
    {
        return $this->getStaticByKey('templates');
    }

    /**
     * @return array<array-key, mixed>
     */
    private function getStaticByKey(string $key): array
    {
        $data = $this->get();

        if (array_key_exists($key, $data)) {
            if (!is_array($data[$key])) {
                throw new ApiException("Static data collection '{$key}' must be an array.");
            }

            return $data[$key];
        }

        throw new ApiException("Static data not found");
    }

}
