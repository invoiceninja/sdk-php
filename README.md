# Invoice Ninja SDK v5!

### Installation

Add the Invoice Ninja SDK

    composer require invoiceninja/sdk-php

### Setup
```php

use InvoiceNinja\Sdk\InvoiceNinja;
   
$ninja = new InvoiceNinja("your_token");
$invoices = $ninja->invoices->all();

```
- To connect to a self hosted version use `->setUrl()` on the $ninja object.

### Supports

- Clients
- Invoices/Quotes
- Products
- Payments
- TaxRates

### Retrieving Models

Retrieve all clients
```php
    $clients = $ninja->clients->all();
```

Retrieve a client by its primary key.
```php
    $client = $ninja->clients->get("CLIENT_HASHED_ID");
```

Retrieving an invoice by it's number:
```php
    $client = $ninja->invoices->all(['number' => '0001']);
```

### Inserting & Updating Models

Create a new client
```php
    $client = $ninja->clients->create(['name' => 'A new client']);

```
Update an existing client
```php
    $client = $ninja->clients->update("CLIENT_HASHED_ID",['name' => 'A client with a updated name']);

```
Create an invoice
```php
    $client = $ninja->invoices->create(['client_id' => CLIENT_HASHED_ID]);

```
Download an invoice PDF
```php
    $pdf = $invoice->download();
```