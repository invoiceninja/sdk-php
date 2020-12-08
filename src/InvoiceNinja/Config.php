<?php namespace InvoiceNinja;

class Config
{
    private static $token;
    private static $url;
    private static $perPage;

    public static function setToken($token)
    {
        static::$token = $token;
    }

    public static function getToken()
    {
        return static::$token;
    }

    public static function setUrl($url)
    {
        static::$url = rtrim($url, '/');
    }

    public static function getUrl()
    {
        return static::$url;
    }

    public static function setPerPage($perPage)
    {
        static::$perPage = $perPage;
    }

    public static function getPerPage()
    {
        return static::$perPage;
    }

    public static function setPage($page)
    {
        static::$page = $page;
    }

    public static function getPage()
    {
        return static::$page;
    }    
}
