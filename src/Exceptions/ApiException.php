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

namespace InvoiceNinja\Sdk\Exceptions;

class ApiException extends \Exception
{

    public static function createFromResponse($response)
    {
        $object = static::parseResponseBody($response);

        $field = null;
        if (! empty($object->field)) {
            $field = $object->field;
        }
// die(var_dump($object));

        return new self(
            "Error executing API call ({$object->message})}",
            $response->getStatusCode(),
            $field
        );
    }

    protected static function parseResponseBody($response)
    {
        $body = (string) $response->getBody();

        $object = @json_decode($body);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new self("Unable to decode response: '{$body}'.");
        }

        return $object;
    }
}