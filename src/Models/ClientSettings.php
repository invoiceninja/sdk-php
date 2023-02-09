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

class ClientSettings extends BaseModel
{

    /**
     * The language ID for the client, for the full
     * list of languages, please see this resource - optional
     *
     * @link https://invoiceninja.github.io/docs/statics/#languages
     * 
     */
    public ?string $language_id = null;

    /**
     * The currency id - REQUIRED
     * 
     * @link https://invoiceninja.github.io/docs/statics/#currencies
     */
    public ?string $currency_id = null;

    /**
     * The payment terms - in days - optional
     *
     * example '10'
     *
     * Due in 10 days
     * 
     */
    public ?string $payment_terms = null;

    /**
     * The quote terms, - optional
     *
     * How many days the quote will be valid for
     *
     * example '10'
     *
     * Quote will expire in 10 days
     * 
     */
    public ?string $valid_until = null;

    /**
     * The task rate for this client. - optional
     *
     * A value of 0 equates to disabled. 
     * 
     */
    public float $default_task_rate = 0;

    /**
     * Whether the client will receive reminders - optional
     *
     * When left unset, this setting will rely on the company
     * settings as the override/default
     */
    public bool $send_reminders; 

}