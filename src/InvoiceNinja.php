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

namespace InvoiceNinja\Sdk;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use InvoiceNinja\Sdk\Endpoints\Tasks;
use InvoiceNinja\Sdk\Endpoints\Users;
use InvoiceNinja\Sdk\Endpoints\Quotes;
use InvoiceNinja\Sdk\Endpoints\Clients;
use InvoiceNinja\Sdk\Endpoints\Credits;
use InvoiceNinja\Sdk\Endpoints\Statics;
use InvoiceNinja\Sdk\Endpoints\Vendors;
use InvoiceNinja\Sdk\Endpoints\Expenses;
use InvoiceNinja\Sdk\Endpoints\Invoices;
use InvoiceNinja\Sdk\Endpoints\Payments;
use InvoiceNinja\Sdk\Endpoints\Products;
use InvoiceNinja\Sdk\Endpoints\Projects;
use InvoiceNinja\Sdk\Endpoints\TaxRates;
use GuzzleHttp\Exception\GuzzleException;
use InvoiceNinja\Sdk\Endpoints\Companies;
use InvoiceNinja\Sdk\Endpoints\Locations;
use InvoiceNinja\Sdk\Endpoints\GroupSettings;
use InvoiceNinja\Sdk\Endpoints\Subscriptions;
use InvoiceNinja\Sdk\Exceptions\ApiException;
use InvoiceNinja\Sdk\Endpoints\PurchaseOrders;
use InvoiceNinja\Sdk\Endpoints\BankIntegrations;
use InvoiceNinja\Sdk\Endpoints\BankTransactions;
use InvoiceNinja\Sdk\Endpoints\ExpenseCategories;
use InvoiceNinja\Sdk\Endpoints\RecurringInvoices;

class InvoiceNinja
{

	private string $endpoint_url = 'https://invoicing.co';

	private string $token = "";

	/** @var array<string, string> */
	private array $headers = [];

	/** @var array<string, mixed> */
	private array $options = [];

	private Client $httpClient;

	public Clients $clients;

	public Invoices $invoices;

	public Products $products;

	public Quotes $quotes;

	public Payments $payments;

	public TaxRates $tax_rates;

	public Statics $statics;

	public Expenses $expenses;

	public RecurringInvoices $recurring_invoices;

	public Credits $credits;

	public Projects $projects;

	public Tasks $tasks;

	public Vendors $vendors;

	public Users $users;

	public Companies $companies;

	public ExpenseCategories $expense_categories;

	public GroupSettings $group_settings;

	public Subscriptions $subscriptions;

	public PurchaseOrders $purchase_orders;

	public BankTransactions $bank_transactions;

	public BankIntegrations $bank_integrations;

    public Locations $locations;
	
    /**
     * @param string $token
     * @return void 
     */
    public function __construct(string $token)
    {
    	$this->token = $token;

    	$this->initialize();
    }

    /**
     * @return $this 
     */
    private function initialize()
    {
    	$this->clients = new Clients($this);
    	$this->invoices = new Invoices($this);
    	$this->products = new Products($this);
    	$this->quotes = new Quotes($this);
    	$this->payments = new Payments($this);
    	$this->tax_rates = new TaxRates($this);
    	$this->statics = new Statics($this);
    	$this->expenses = new Expenses($this);
    	$this->recurring_invoices = new RecurringInvoices($this);
    	$this->credits = new Credits($this);
    	$this->projects = new Projects($this);
    	$this->tasks = new Tasks($this);
    	$this->vendors = new Vendors($this);
    	$this->users = new Users($this);
    	$this->companies = new Companies($this);
    	$this->expense_categories = new ExpenseCategories($this);
    	$this->group_settings = new GroupSettings($this);
    	$this->subscriptions = new Subscriptions($this);
    	$this->purchase_orders = new PurchaseOrders($this);
    	$this->bank_transactions = new BankTransactions($this);
    	$this->bank_integrations = new BankIntegrations($this);
		$this->locations = new Locations($this);
    	
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

    /**
     * @param string $token
     * @return $this 
     */
    public function setToken(string $token): self
    {
    	$this->token = $token;

    	return $this;
    }

    /**
     * @param array<string, mixed> $options
     */
    public function setOptions(array $options): self
    {
    	$this->options = array_merge($this->options, $options);

    	return $this;
    }

    /**
     * @return array<string, mixed>
     */
    private function getOptions(): array
    {
    	return $this->options;
    }

    /**
     * @return string 
     */
	public function getToken()
	{
		return $this->token;
	}

    /** 
     * @return string  
     */
    private function getUrl() :string
    {
    	return rtrim($this->endpoint_url, '/');
	}
    /**
     * @param array<string, string> $headers
     */
    private function setHeaders(array $headers): self
    {
    	$this->headers = $headers;

    	return $this;
    }

    /**
     * @return array<string, string>
     */
    private function getHeaders(): array
    {
    	return $this->headers;
    }

    /**
     * @param array<string, string> $headers
     */
    public function addHeader(array $headers): self
    {
    	$this->setHeaders(array_merge($this->getHeaders(), $headers));

    	return $this;
    }

    /**
     * @return array<string, string>
     */
    private function buildHeaders(): array
    {
		$headers = [
		    'X-API-TOKEN' 	=> $this->getToken(),        
		    'X-Requested-With' => 'XMLHttpRequest',
		];

		return array_merge($headers, $this->getHeaders());
    }

	/** 
	 * @return self
	 */
	private function httpClient()
	{
		$this->httpClient = new \GuzzleHttp\Client(
			array_merge($this->getOptions(),[
				'headers' => $this->buildHeaders()
			])
		);

		return $this;
	}

    /**
     * @param array<string, mixed> $payload
     * @return array<array-key, mixed>
     * @throws ApiException
     */
    public function send(string $method, string $uri, array $payload = []): array
    {
        $response = $this->request($method, $uri, $payload);
        $body = (string) $response->getBody();

        if ($this->isBodyless($method, $response)) {
            return [];
        }

        try {
            $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR | JSON_BIGINT_AS_STRING);
        } catch (\JsonException $exception) {
            throw $this->failure($method, $uri, $response->getStatusCode(), 'Invalid JSON response.', $exception, $payload);
        }

        if (!is_array($data)) {
            throw $this->failure($method, $uri, $response->getStatusCode(), 'Expected a JSON object or list.', null, $payload);
        }

        return $data;
    }

    /**
     * @param array<string, mixed> $payload
     * @throws ApiException
     */
    public function stream(string $method, string $uri, array $payload = []): string
    {
        return (string) $this->request($method, $uri, $payload)->getBody();
    }

    /** @param array<string, mixed> $payload */
    private function request(string $method, string $uri, array $payload): ResponseInterface
    {
        $this->httpClient();

        try {
            $response = $this->httpClient->request(strtoupper($method), $this->getUrl() . $uri, $payload);
        } catch (GuzzleException $exception) {
            $response = method_exists($exception, 'getResponse') ? $exception->getResponse() : null;

            if ($response === null) {
                // Guzzle messages can contain credentials, URLs and response bodies.
                throw $this->failure($method, $uri, 0, 'Transport failure.', $exception, $payload);
            }

            throw $this->httpFailure($method, $uri, $response, $exception, $payload);
        }

        $status = $response->getStatusCode();
        if ($status < 200 || $status >= 300) {
            throw $this->httpFailure($method, $uri, $response, null, $payload);
        }

        if ($this->isBodyless($method, $response) && (string) $response->getBody() !== '') {
            throw $this->failure($method, $uri, $status, 'Unexpected body for a bodyless response.', null, $payload);
        }

        return $response;
    }

    private function isBodyless(string $method, ResponseInterface $response): bool
    {
        return strtoupper($method) === 'HEAD' || in_array($response->getStatusCode(), [204, 205], true);
    }

    /** @param array<string, mixed> $payload */
    private function httpFailure(string $method, string $uri, ResponseInterface $response, ?\Throwable $previous, array $payload): ApiException
    {
        $exception = ApiException::createFromResponse($response, $previous);

        return $this->failure($method, $uri, $response->getStatusCode(), 'Unexpected HTTP status. ' . $exception->getMessage(), $exception->getPrevious(), $payload);
    }

    /** @param array<string, mixed> $payload */
    private function failure(string $method, string $uri, int $status, string $detail, ?\Throwable $previous, array $payload): ApiException
    {
        // Keep the route useful without disclosing the origin, userinfo or query.
        $path = parse_url($uri, PHP_URL_PATH);
        $path = is_string($path) ? $path : '/';
        $message = strtoupper($method) . ' ' . $path . ' (HTTP ' . $status . '): ' . $detail;
        $secrets = [$this->token];

        foreach ([$this->buildHeaders(), $this->options['headers'] ?? [], $payload['headers'] ?? []] as $headers) {
            if (is_array($headers)) {
                foreach ($headers as $values) {
                    foreach ((array) $values as $value) {
                        if (is_string($value)) {
                            $secrets[] = $value;
                        }
                    }
                }
            }
        }

        // Redact before bounding the diagnostic, including URL-encoded tokens.
        foreach ($secrets as $secret) {
            if ($secret !== '') {
                $message = str_replace([$secret, rawurlencode($secret), urlencode($secret)], '[redacted]', $message);
            }
        }

        $message = preg_replace('~https?://[^\s]+~i', '[url redacted]', $message) ?? '';
        $message = preg_replace('/[\x00-\x1f\x7f]/', ' ', $message) ?? '';

        return new ApiException(substr($message, 0, 512), $status, $previous);
    }
}
