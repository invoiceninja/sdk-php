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

namespace InvoiceNinja\Sdk\Models;

use InvoiceNinja\Sdk\Exceptions\ModelValidationException;

class Webhook extends BaseModel
{
    protected string $model = 'Webhook';

    // Start of event constants
    // These values can be copied from: https://github.com/invoiceninja/invoiceninja/blob/v5-stable/app/Models/Webhook.php
    public const EVENT_CREATE_CLIENT = 1;
    public const EVENT_CREATE_INVOICE = 2;
    public const EVENT_CREATE_QUOTE = 3;
    public const EVENT_CREATE_PAYMENT = 4;
    public const EVENT_CREATE_VENDOR = 5;
    public const EVENT_UPDATE_QUOTE = 6;
    public const EVENT_DELETE_QUOTE = 7;
    public const EVENT_UPDATE_INVOICE = 8;
    public const EVENT_DELETE_INVOICE = 9;
    public const EVENT_UPDATE_CLIENT = 10;
    public const EVENT_DELETE_CLIENT = 11;
    public const EVENT_DELETE_PAYMENT = 12;
    public const EVENT_UPDATE_VENDOR = 13;
    public const EVENT_DELETE_VENDOR = 14;
    public const EVENT_CREATE_EXPENSE = 15;
    public const EVENT_UPDATE_EXPENSE = 16;
    public const EVENT_DELETE_EXPENSE = 17;
    public const EVENT_CREATE_TASK = 18;
    public const EVENT_UPDATE_TASK = 19;
    public const EVENT_DELETE_TASK = 20;
    public const EVENT_APPROVE_QUOTE = 21;
    public const EVENT_LATE_INVOICE = 22;
    public const EVENT_EXPIRED_QUOTE = 23;
    public const EVENT_REMIND_INVOICE = 24;
    public const EVENT_PROJECT_CREATE = 25;
    public const EVENT_PROJECT_UPDATE = 26;
    public const EVENT_CREATE_CREDIT = 27;
    public const EVENT_UPDATE_CREDIT = 28;
    public const EVENT_DELETE_CREDIT = 29;
    public const EVENT_PROJECT_DELETE = 30;
    public const EVENT_UPDATE_PAYMENT = 31;
    public const EVENT_ARCHIVE_PAYMENT = 32;
    public const EVENT_ARCHIVE_INVOICE = 33;
    public const EVENT_ARCHIVE_QUOTE = 34;
    public const EVENT_ARCHIVE_CREDIT = 35;
    public const EVENT_ARCHIVE_TASK = 36;
    public const EVENT_ARCHIVE_CLIENT = 37;
    public const EVENT_ARCHIVE_PROJECT = 38;
    public const EVENT_ARCHIVE_EXPENSE = 39;
    public const EVENT_RESTORE_PAYMENT = 40;
    public const EVENT_RESTORE_INVOICE = 41;
    public const EVENT_RESTORE_QUOTE = 42;
    public const EVENT_RESTORE_CREDIT = 43;
    public const EVENT_RESTORE_TASK = 44;
    public const EVENT_RESTORE_CLIENT = 45;
    public const EVENT_RESTORE_PROJECT = 46;
    public const EVENT_RESTORE_EXPENSE = 47;
    public const EVENT_ARCHIVE_VENDOR = 48;
    public const EVENT_RESTORE_VENDOR = 49;
    public const EVENT_CREATE_PRODUCT = 50;
    public const EVENT_UPDATE_PRODUCT = 51;
    public const EVENT_DELETE_PRODUCT = 52;
    public const EVENT_RESTORE_PRODUCT = 53;
    public const EVENT_ARCHIVE_PRODUCT = 54;
    public const EVENT_CREATE_PURCHASE_ORDER = 55;
    public const EVENT_UPDATE_PURCHASE_ORDER = 56;
    public const EVENT_DELETE_PURCHASE_ORDER = 57;
    public const EVENT_RESTORE_PURCHASE_ORDER = 58;
    public const EVENT_ARCHIVE_PURCHASE_ORDER = 59;
    public const EVENT_SENT_INVOICE = 60;
    public const EVENT_SENT_QUOTE = 61;
    public const EVENT_SENT_CREDIT = 62;
    public const EVENT_SENT_PURCHASE_ORDER = 63;
    public const EVENT_REMIND_QUOTE = 64;
    public const EVENT_ACCEPTED_PURCHASE_ORDER = 65;

    // End of event constants

    /**
     *  The event id - required
     */
    public int $event_id;

    /**
     *  The target url - required
     */
    public string $target_url;

    /**
     *  The request format - default: JSON
     */
    public ?string $format = 'JSON';

    /**
     *  The REST method - default: post
     */
    public ?string $rest_method = 'post';

    /**
     *  The headers - default: empty
     */
    public ?array $headers = [];

    protected array $rules = [
        'event_id' => 'required',
        'target_url' => 'required',
    ];

    /**
     * @throws ModelValidationException
     */
    public function save()
    {
        $this->validate();
    }
}
