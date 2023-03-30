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

namespace InvoiceNinja\Sdk\Exceptions;

class ApiException extends \Exception
{

    public static function createFromResponse($response)
    {
        $error = static::parseResponseBody($response);

        $field = null;

        return new self(
            "Error executing API call ({$error})}",
            $response->getStatusCode(),
            $field
        );
    }

    protected static function parseResponseBody($response)
    {
        $body = (string) $response->getBody();
        $error = "";
        $object = @json_decode($body);
        $error_array = [];

        if(property_exists($object, "message"))
            $error = $object->message;

        if(property_exists($object, "errors"))
        {
            $properties = array_keys(get_object_vars($object->errors));

            if(is_array($properties) && count($properties) >=1)
                $error_array = $object->errors->{$properties[0]};

            if(is_array($error_array) && count($error_array) >=1)
                $error = $error_array[0];

        }

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new self("Unable to decode response: '{$body}'.");
        }

        return $error;
    }
}