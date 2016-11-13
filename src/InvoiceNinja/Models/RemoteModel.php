<?php namespace InvoiceNinja\Models;

use Exception;

class RemoteModel
{
    public static $route = false;
    public static $include = false;

    public $id;

    /**
    * @return array
    */
    public static function all()
    {
        $url = static::getRoute();
        $data = static::sendRequest($url);

        $result = [];
        foreach ($data as $item) {
            $result[] = static::hydrate($item);
        }

        return $result;
    }

    /**
    * @return \InvoiceNinja\Models\RemoteModel
    */
    public static function find($id)
    {
        $url = static::getRoute() . '/' . $id;
        $data = static::sendRequest();

        return static::hydrate($data);
    }

    /**
    * @return \InvoiceNinja\Models\RemoteModel
    */
    public function save()
    {
        $url = static::getRoute();

        if ($this->id) {
            $method = 'PUT';
            $url .= '/' . $this->id;
        } else {
            $method = 'POST';
        }

        $data = static::sendRequest($url, $this, $method);

        return static::hydrate($data, $this);
    }

    private static function getRoute()
    {
        if ( ! static::$route) {
            throw new Exception('API route is not defined for ' . get_called_class());
        }

        return sprintf('%s/api/v1/%s', $_ENV['NINJA_URL'], static::$route);
    }

    private static function hydrate($item, $entity = false)
    {
        if ( ! $entity) {
            $className = get_called_class();
            $entity = new $className();
        }

        foreach ($item as $key => $value) {
            $entity->$key = $value;
        }

        return $entity;
    }

    private static function sendRequest($url, $data = false, $type = 'GET')
    {
        $data = json_encode($data);
        $curl = curl_init();

        if (static::$include) {
            $url .= '?include=' . static::$include;
        }

        $opts = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $type,
            CURLOPT_POST => $type === 'POST' ? 1 : 0,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER  => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data),
                'X-Ninja-Token: '. $_ENV['NINJA_TOKEN'],
            ],
        ];

        curl_setopt_array($curl, $opts);
        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response)->data;
    }

}
