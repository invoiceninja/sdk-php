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

    public int $status_id = self::STATUS_DRAFT;

    public ?string $number = null;

    public float $discount = 0;

    public bool $is_amount_discount = true;

    public string $po_number = '';

    public string $footer = '';

    public string $terms = '';

    public string $public_notes = '';

    public string $private_notes = '';

    public ?string $date = null;

    public ?string $due_date = null;

    public float $partial = 0;

    public ?string $partial_due_date = null;

    public bool $is_deleted = false;

    public array $line_items = [];

    public string $tax_name1 = '';

    public float $tax_rate1 = 0;

    public string $tax_name2 = '';

    public float $tax_rate2 = 0;

    public string $tax_name3 = '';

    public float $tax_rate3 = 0;

    public string $custom_value1 = '';

    public string $custom_value2 = '';

    public string $custom_value3 = '';

    public string $custom_value4 = '';

    public float $exchange_rate = 1;

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