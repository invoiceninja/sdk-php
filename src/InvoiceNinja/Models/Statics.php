<?php namespace InvoiceNinja\Models;

use stdClass;

class Statics extends AbstractModel
{
    public static $route = 'static';

    public static function all()
    {
        $url = static::getRoute() . '/';
        
        return static::sendRequest($url, false, 'GET', true);
    }

    public static function get($static)
    {
        return json_decode(static::all(), true)['data'][$static];
    }

    public static function countries()
    {
        return static::get('countries');
    }

    public static function currencies()
    {
        return static::get('currencies');
    }

    public static function sizes()
    {
        return static::get('sizes');
    }

    public static function industries()
    {
        return static::get('industries');
    }

    public static function timezones()
    {
        return static::get('timezones');
    }

    public static function dateFormats()
    {
        return static::get('dateFormats');
    }

    public static function datetimeFormats()
    {
        return static::get('datetimeFormats');
    }

    public static function languages()
    {
        return static::get('languages');
    }

    public static function paymentTerms()
    {
        return static::get('paymentTypes');
    }

    public static function invoiceDesigns()
    {
        return static::get('invoiceDesigns');
    }

    public static function invoiceStatus()
    {
        return static::get('invoiceStatus');
    }

    public static function frequencies()
    {
        return static::get('frequencies');
    }

    public static function gateways()
    {
        return static::get('gateways');
    }

    public static function fonts()
    {
        return static::get('fonts');
    }

    public static function banks()
    {
        return static::get('banks');
    }
}
