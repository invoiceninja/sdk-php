<?php namespace InvoiceNinja\Models;

use stdClass;

class EmailInvoice extends AbstractModel
{
    public static $route = 'email_invoice';

    public function __construct()
    {

    }

    public static function send(Invoice $invoice)
	{
		$url = static::getRoute();
		$data = [
			'id' => $invoice->id
		];

		return static::sendRequest($url, $data, 'POST', true);

	}
}
