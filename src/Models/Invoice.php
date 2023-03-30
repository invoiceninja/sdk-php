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

use InvoiceNinja\Sdk\Models\Client;

class Invoice extends BaseModel
{

    protected string $model = 'Invoice';

    public const STATUS_DRAFT = 1;


    /**
     *  The status of the invoice - readonly
     *
     *  STATUS_DRAFT = 1;
     *  STATUS_SENT = 2;
     *  STATUS_PARETIAL = 3;
     *  STATUS_PAID = 4;
     */
    public int $status_id = self::STATUS_DRAFT;

    /**
     * Invoice number - optional
     *
     * The system will apply this if no invoice number is set.
     * The invoice number _must_ be unique else a validation
     * error will be returned
     */
    public ?string $number = null;

    /**
     * Discount - optional
     * 
     */
    public float $discount = 0;

    /**
     * Is amount discount - optional
     *
     * If set true, a discount is set in a currency amount
     * If set false a discount is set in a percentage
     */
    public bool $is_amount_discount = true;

    /**
     * Purchase order - optional
     * 
     */
    public string $po_number = '';

    /**
     * Invoice footer - optional
     *
     * If a default company footer is set, then it will
     * be applied if nothing is set
     */
    public string $footer = '';


    /**
     * Invoice terms - optional
     *
     * If a default company terms is set, then it will
     * be applied if nothing is set
     */
    public string $terms = '';

    /**
     * Public notes - optional
     * 
     */
    public string $public_notes = '';

    /**
     * Private notes - optional
     * 
     */
    public string $private_notes = '';

    /**
     * The invoice date - optional
     *
     * format Y-m-d
     *
     * If left blank the API will set todays date
     */
    public ?string $date = null;


    /**
     * The invoice due date - optional
     *
     * format Y-m-d
     *
     * If left blank the API will set the due date
     * based on the default payment terms - if set
     */
    public ?string $due_date = null;

    /**
     * Partial / Deposit - optional
     * 
     */
    public float $partial = 0;

    /**
     * The invoice partial due date - optional
     *
     * format Y-m-d
     *
     * The date which the partial / deposit is due
     */
    public ?string $partial_due_date = null;

    /**
     * A boolean flag which defines if the invoice 
     * is deleted
     * 
     * @property-read bool $is_deleted
     */
    public bool $is_deleted = false;

    /**
     * A array of invoice items - optional
     * 
     * @var InvoiceItem[]
     */
    public array $line_items = [];

    /**
     * Tax Name 1 name - optional
     *
     * ie GST
     */
    public string $tax_name1 = '';

    /**
     * Tax Rate 1 - optional
     *
     * ie 10
     */
    public float $tax_rate1 = 0;

    /**
     * Tax Name 2 name - optional
     *
     * ie GST
     */
    public string $tax_name2 = '';

    /**
     * Tax Rate 2 - optional
     *
     * ie 10
     */
    public float $tax_rate2 = 0;


    /**
     * Tax Name 3 name - optional
     *
     * ie GST
     */
    public string $tax_name3 = '';

    /**
     * Tax Rate 3 - optional
     *
     * ie 10
     */
    public float $tax_rate3 = 0;

    /**
     * Custom value - optional
     * 
     */
    public ?string $custom_value1 = null;

    /**
     * Custom value - optional
     * 
     */
    public ?string $custom_value2 = null;

    /**
     * Custom value - optional
     * 
     */
    public ?string $custom_value3 = null;

    /**
     * Custom value - optional
     * 
     */
    public ?string $custom_value4 = null;

    /**
     * Exchange rate - optional
     *
     * default 1
     */
    public float $exchange_rate = 1;

    /**
     * Client ID - required
     *
     * The hashed ID of the client
     * 
     */
    public ?string $client_id = null;

    protected array $rules = [
        'client_id' => 'required',
        'date' => 'date',
        'due_date' => 'date',
        'partial_due_date' => 'date',
    ];

    private array $validation_errors = [];

    public function __construct()
    {

    }

    public function setClient(Client $client): self
    {
        $this->client_id = $client->id;

        return $this;
    }

    public function setClientId(string $client_id): self
    {
        $this->client_id = $client->id;

        return $this;
    }

    public function addLine(InvoiceItem $item)
    {
        $this->line_items = array_merge($this->line_items, $item);

        return $this;
    }


    //////////////////////////////////////// helpers - for abstraction///////////////////////////////////////////




}