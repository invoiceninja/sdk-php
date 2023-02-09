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
     * @var ClientContact[]
     */
    public array $contacts = [];

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
