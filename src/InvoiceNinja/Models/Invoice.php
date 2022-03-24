<?php namespace InvoiceNinja\Models;

use stdClass;

class Invoice extends AbstractModel
{
    public static $route = 'invoices';
    public static $include = 'invitations';

    public $invoice_items = [];
    public $invitations = [];

    public function __construct()
    {

    }

    public function addInvoiceItem($product_key, $notes, $cost, $qty = 1,$tax_rate=0,$tax_name='')
    {
        $item = new stdClass();
        $item->product_key = $product_key;
        $item->notes = $notes;
        $item->cost = $cost;
        $item->qty = $qty;
        $item->tax_rate1 = $tax_rate;
        $item->tax_name1 = $tax_name;

        $this->invoice_items[] = $item;
    }

    public function download()
    {
        $url = static::getRoute() . '/' . $this->id;
        $url = str_replace('invoices', 'download', $url);

        return static::sendRequest($url, false, 'GET', true);
    }
}
