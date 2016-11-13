<?php namespace InvoiceNinja;

use InvoiceNinja\Models\RemoteModel;

class Config
{
    public static function setToken($token)
    {
        RemoteModel::$token = $token;
    }

    public static function setUrl($url)
    {
        RemoteModel::$url = rtrim($url, '/');
    }
}
