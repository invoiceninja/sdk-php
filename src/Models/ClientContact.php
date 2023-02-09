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

class ClientContact extends BaseModel
{
    protected string $model = 'ClientContact';

    /**
     * The first name of the contact - optional
     * 
     */
    public string $first_name = '';
    
    /**
     * The last name of the contact - optional
     * 
     */
    public string $last_name = '';
    
    /**
     * Email - optional
     * 
     */
    public string $email = '';

    /**
     * Phone number - optional
     * 
     */
    public string $phone = '';

    /**
     * Flag for whether the contact will 
     * receive emails. - optional 
     *
     * default - true
     * 
     */
    public bool $send_email = true;

    public string $custom_value1 = '';

    public string $custom_value2 = '';

    public string $custom_value3 = '';

    public string $custom_value4 = '';

}