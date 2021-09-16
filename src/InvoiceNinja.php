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
use InvoiceNinja\Sdk\Endpoints\Clients;
use InvoiceNinja\Sdk\Endpoints\Invoices;
use InvoiceNinja\Sdk\Endpoints\Payments;
use InvoiceNinja\Sdk\Endpoints\Products;
use InvoiceNinja\Sdk\Endpoints\Quotes;
use InvoiceNinja\Sdk\Endpoints\TaxRates;
use InvoiceNinja\Sdk\Exceptions\ApiException;

class InvoiceNinja
{

	private string $endpoint_url = 'https://invoicing.co';

	private string $token = "";

	private array $headers = [];

	private Client $httpClient;

	public Clients $clients;

	public Invoices $invoices;

	public Products $products;

	public Quotes $quotes;

	public Payments $payments;

	public TaxRates $tax_rates;

    /**
     * @param string $token 
     * @return void 
     */
    public function __construct(string $token)
    {
    	$this->token = $token;

    	$this->initialize();
    }

    private function initialize()
    {
    	$this->clients = new Clients($this);
    	$this->invoices = new Invoices($this);
    	$this->products = new Products($this);
    	$this->quotes = new Quotes($this);
    	$this->payments = new Payments($this);
    	$this->tax_rates = new TaxRates($this);

    	return $this;
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
		$this->httpClient = new \GuzzleHttp\Client(['headers' => $this->buildHeaders()]);

		return $this;
	}

	/**
	 * @param string $method 
	 * @param string $uri 
	 * @param array $payload 
	 * @return void 
	 * @throws GuzzleException 
	 */
	public function send(string $method, string $uri, array $payload)
	{
		$this->httpClient();

		$url = $this->getUrl() . $uri;

		try{
		
			$response =  $this->httpClient->request($method, $url, $payload);
		
			if($response->getStatusCode() == 200)	
				return json_decode($response->getBody()->getContents(),true);

			//get appropriate response for status and return

		}
		catch(GuzzleException $e) {
			
            if (method_exists($e, 'hasResponse') && method_exists($e, 'getResponse') && $e->hasResponse()) 
            	throw ApiException::createFromResponse($e->getResponse());
                

            throw new ApiException($e->getMessage(), $e->getCode());


		}
	}

}


