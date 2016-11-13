# Invoice Ninja SDK

**We're starting work on custom modules, join the discussion [here](https://github.com/invoiceninja/invoiceninja/issues/1131).**

### Installation

Add the Invoice Ninja SDK

    composer require invoiceninja/sdk-php

### Setup

    use InvoiceNinja\Config as NinjaConfig;
    use InvoiceNinja\Models\Client;

    NinjaConfig::setURL('https://ninja.dev/api/v1');
    NinjaConfig::setToken('Your Token');

- To connect to the hosted version use `https://app.invoiceninja.com/api/v1` as the URL.
- You can either use the [free hosted trial](https://app.invoiceninja.com/invoice_now?sign_up=true&redirect_to=/settings/api_tokens) or [install the app](https://www.invoiceninja.com/self-host/) to create an API token.

### Search

Find all clients

    $clients = Client::all();

Find one client by it's id

    $client = Client::find(1);

### Create and Update

Create a new client

    $client = new Client('test@example.com');
    $client->save();

Update an existing client

    $client->vat_number = '123456';
    $client->save();

Create an invoice

    $invoice = $client->createInvoice();
    $invoice->addInvoiceItem('Item', 'Some notes', 10);
    $invoice->save();

Download an invoice PDF

    $pdf = $invoice->download();

### Archive, Delete and Restore

    $client->archive();
    $client->delete();
    $client->restore();

### Supports

- Clients
- Invoices/Quotes
- Products
- Payments
- Tasks
- Vendors
- Expenses
- TaxRates

## To Do

- Support registering for events
- Improve error handling
- Create documentation
