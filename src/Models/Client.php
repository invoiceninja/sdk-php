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

use InvoiceNinja\Sdk\Models\ClientContact;
use InvoiceNinja\Sdk\Models\ClientSettings;

class Client extends BaseModel
{
    protected string $model = 'Client';

    /**
     *  The client name - required
     */
    public ?string $name = null;

    /**
     * The website ie https://example.com - optional
     */
    public ?string $website = null;

    /**
     * Private Notes - optional
     */
    public ?string $private_notes = null;
    /**
     * Public Notes - optional
     */
    public ?string $public_notes = null;

    /**
     * Phone - optional
     */
    public ?string $phone = null;

    /**
     * VAT/GST/Tax number - optional
     */
    public ?string $vat_number = null;

    /**
     * ID number - optional
     */
    public ?string $id_number = null;

    /**
     * Number - optional
     */
    public ?string $number = null;

    /**
     * Custom value 1 - optional
     */
    public ?string $custom_value1 = null;

    /**
     * Custom value 2 - optional
     */
    public ?string $custom_value2 = null;

    /**
     * Custom value 3 - optional
     */
    public ?string $custom_value3 = null;
    
    /**
     * Custom value 4 - optional
     */    
    public ?string $custom_value4 = null;

    /**
     * The array of client contacts. This is optional, however
     * the API will always set a blank ClientContact if none are set
     * 
     * @var ClientContact[]
     */
    public array $contacts = [];

    /**
     * Industry ID - optional
     */
    public ?int $industry_id = null;

    /**
     * Size ID - optional
     */
    public ?int $size_id = null;

    /**
     * Address line 1 - optional
     */
    public ?string $address1 = null;

    /**
     * Address line 2 - optional
     */
    public ?string $address2 = null;
    
    /**
     * City - optional
     */
    public ?string $city = null;
    
    /**
     * State - optional
     */
    public ?string $state = null;
    
    /**
     * Postal Code - optional
     */
    public ?string $postal_code = null;

    /**
     * Country ID - optional|autoset If no value is set
     * then the system will apply the company country ID.
     * For the full list of country IDs you can use this reference
     * 
     * https://invoiceninja.github.io/docs/statics/#page-content
     */
    public ?string $country_id = null;

    /**
     * Country Code - optional iso_3166_2 or iso_3166_3 country code
     * This can be used optionally instead of the country_id field
     */
    public ?string $country_code = null;

    /**
     * Shipping Address line 1 - optional
     */
    public ?string $shipping_address1 = null;

    /**
     * Shipping Address line 2 - optional
     */
    public ?string $shipping_address2 = null;
    
    /**
     * Shipping City - optional
     */
    public ?string $shipping_city = null;
    
    /**
     * Shipping State - optional
     */
    public ?string $shipping_state = null;
    
    /**
     * Shipping Postal Code - optional
     */
    public ?string $shipping_postal_code = null;

    /**
     * Shipping Country ID - optional
     *      
     * https://invoiceninja.github.io/docs/statics/#page-content
     */
    public ?string $country_id = null;

    /**
     * @property-read float $balance - The client Balance
     */

    public float $balance;

    /**
     * @property-read float $credit_balance - The client credit_balance
     */

    public float $credit_balance;

    /**
     * @property-read float $paid_to_date - The client paid_to_date
     */

    public float $paid_to_date;

    /**
     * @var ClientSettings $settings
     *
     * The client settings object
     */

    public ClientSettings $client_settings;
    


























































    protected array $rules = [
        'name' => 'required',
    ];

    public function __construct()
    {

    }

    public function setContact(ClientContact $contact): self
    {
        $this->contacts = array_merge($this->contacts, $contact);

        return $this;
    }

    public function getContacts(): array
    {
        return $this->contacts;
    }

    public function save()
    {
        $this->validate();
    }
}
