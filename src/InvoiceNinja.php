<?php
/**
 * Invoice Ninja (https://invoiceninja.com).
 *
 * @link https://github.com/invoiceninja/sdk-php source repository
 *
 * @copyright Copyright (c) 2021. Invoice Ninja LLC (https://invoiceninja.com)
 *
 * @license https://opensource.org/licenses/MIT
 */

namespace InvoiceNinja\Sdk;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class InvoiceNinja
{

	private string $endpoint_url = 'https://invoicing.co';

	private string $token = "";

	private array $headers = [];

    /**
     * @param string $token 
     * @return void 
     */
    public function __construct(string $token)
    {
    	$this->token = $token;
    }

    /**
     * @param string $url 
     * @return $this 
     */
    public function setUrl(string $url)
    {
    	$this->endpoint_url = $url;

    	return $this;
    }

    /** @return string  */
    private function getUrl() :string
    {
    	return $this->endpoint_url;
    }

    /**
     * @param array $headers 
     * @return $this 
     */
    private function setHeaders(array $headers)
    {
    	$this->headers = $headers;

    	return $this;
    }

    /** @return array  */
    private function getHeaders()
    {
    	return $this->headers;
    }

    /**
     * @param array $headers 
     * @return $this 
     */
    public function addHeader(array $headers)
    {
    	$this->setHeaders(array_merge($this->getHeaders(), $headers));

    	return $this;
    }

    /** @return array  */
    private function buildHeaders() :array
    {
		$headers = [
		    'X-API-TOKEN' 	=> $this->token,        
		    'X-Requested-With' => 'XMLHttpRequest',
		];

		return array_merge($headers, $this->getHeaders());
    }

	/** @return Client  */
	private function httpClient()
	{
		return new \GuzzleHttp\Client(['headers' => $this->buildHeaders()]);
	}

	/**
	 * @param string $method 
	 * @param string $uri 
	 * @param array $payload 
	 * @return void 
	 * @throws GuzzleException 
	 */
	public function run(string $method, string $uri, array $payload)
	{
		$url = $this->getUrl . $uri;

		try{
			$this->httpClient()->request($method, $url, $payload);
		}
		catch(RequestException $e) {

		}
	}

}