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

class InvoiceItem extends BaseModel
{
    protected string $model = 'InvoiceItem';

    public const TYPE_PRODUCT = '1';

    public const TYPE_SERVICE = '2';

    public const TYPE_UNPAID_GATEWAY_FEE = '3';
    
    public const TYPE_PAID_GATEWAY_FEE = '4';

    public const TYPE_LATE_FEE = '5';

    public const TYPE_EXPENSE = '6';

    public float $quantity = 0;

    public float $cost = 0;

    public string $product_key = '';

    public float $product_cost = 0;

    public string $notes = '';

    public float $discount = 0;

    public bool $is_amount_discount = false;

    public string $tax_name1 = '';

    public float $tax_rate1 = 0;

    public string $tax_name2 = '';

    public float $tax_rate2 = 0;

    public string $tax_name3 = '';

    public float $tax_rate3 = 0;

    public string $sort_id = '0';

    public float $line_total = 0;

    public float $gross_line_total = 0;
    
    public float $tax_amount = 0;

    public string $date = '';

    public string $custom_value1 = '';

    public string $custom_value2 = '';

    public string $custom_value3 = '';

    public string $custom_value4 = '';

    public $type_id = self::TYPE_PRODUCT;

}
