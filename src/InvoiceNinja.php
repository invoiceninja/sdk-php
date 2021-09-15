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

class InvoiceNinja
{

	private string $endpoint_url = 'https://invoicing.co';

	private string $token;

    public function __construct($token)
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
    
}