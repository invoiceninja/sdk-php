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

use Psr\Http\Message\ResponseInterface;

class ApiException extends \Exception
{
    public static function createFromResponse(ResponseInterface $response, ?\Throwable $previous = null): self
    {
        try {
            $detail = static::parseResponseBody($response);
        } catch (\JsonException $exception) {
            $detail = 'Unable to decode response';
            $previous ??= $exception;
        }

        return new self(
            'Error executing API call (' . $detail . ')',
            $response->getStatusCode(),
            $previous
        );
    }

    protected static function parseResponseBody(ResponseInterface $response): string
    {
        $body = (string) $response->getBody();
        $object = json_decode($body, false, 512, JSON_THROW_ON_ERROR | JSON_BIGINT_AS_STRING);

        if (!is_object($object)) {
            return 'Unable to decode response';
        }

        $error = is_string($object->message ?? null) ? $object->message : '';

        if (isset($object->errors) && is_object($object->errors)) {
            $fields = get_object_vars($object->errors);
            $messages = reset($fields);

            if (is_array($messages) && isset($messages[0]) && is_string($messages[0])) {
                $error = $messages[0];
            }
        }

        return $error;
    }
}
