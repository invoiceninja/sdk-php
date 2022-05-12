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

namespace InvoiceNinja\Sdk\Endpoints;

use GuzzleHttp\Exception\GuzzleException;
use InvoiceNinja\Sdk\Exceptions\ApiException;
use InvoiceNinja\Sdk\InvoiceNinja;

class Statics
{

    protected InvoiceNinja $ninja;

    private array $statics;

    private array $keys = [
        'currencies', 
        'industries', 
        'languages', 
        'countries', 
        'banks', 
        'payment_types', 
        'templates'
    ];

    public function __construct(InvoiceNinja $ninja)
    {
        $this->ninja = $ninja;
    }

    private function get(array $search = []) 
    {
        return $this->ninja->send("GET", "/api/v1/statics", $search);
    }

    public function currencies()
    {
        return $this->getStaticByKey('currencies');
    }

    public function industries()
    {
        return $this->getStaticByKey('industries');
    }

    public function languages()
    {
        return $this->getStaticByKey('languages');
    }

    public function countries()
    {
        return $this->getStaticByKey('countries');
    }

    public function banks()
    {
        return $this->getStaticByKey('banks');
    }

    public function payment_types()
    {
        return $this->getStaticByKey('payment_types');
    }

    public function templates()
    {
        return $this->getStaticByKey('templates');
    }    

    private function getStaticByKey($key)
    {
        $data = $this->get();

        if(array_key_exists($key, $data))
            return $data[$key];

        throw new ApiException("Static data not found");
    }

}

