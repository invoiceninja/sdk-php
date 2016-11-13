# Invoice Ninja SDK

### Installation

Add the Invoice Ninja SDK

    composer require invoiceninja/sdk-php

### Setup

    use InvoiceNinja\Config as NinjaConfig;
    use InvoiceNinja\Models\Client;

    NinjaConfig::setURL('http://ninja.dev/api/v1');
    NinjaConfig::setToken('Your Token');

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
