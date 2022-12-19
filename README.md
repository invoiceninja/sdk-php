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
- Invoices
- Quotes
- Products
- Payments
- TaxRates
- Statics
- Expenses
- Expense Categories
- Recurring Invoices
- Credits
- Projects
- Tasks
- Vendors
- Companies
- Subscriptions
- Purchase Orders

### Retrieving Models

Retrieve all clients
```php
    $clients = $ninja->clients->all();
```

You can perform complex filtering on the ->all() method. 

Query parameters can be chained together to form complex queries. The current supported values are:

**per_page**: The number of clients per page you want returned  
**page**: The page number  
**include**: A comma separated list of relations to include ie. contacts,documents,gateway_tokens  
**balance**: A query to return clients with a balance using an operator and value
 - ie ?balance=lt:10 Returns clients with a balance less than 10  
 - available operators lt, lte, gt, gte, eq  

**between_balance**: Returns clients with a balance between two values  
 - ie ?between_balance=10:20 - Returns clients with a balance between 10 and 20  

**email**: Returns clients with a contacts.email field equal to an email  
**id_number**: Search by id_number  
**number**: Search by number  
**filter**: Search across multiple columns (name, id_number, first_name, last_name, email, custom_value1, custom_value2, custom_value3, custom_value4)  
**created_at**: Search by created at (Unix timestamp)  
**is_deleted**: Search using is_deleted boolean flag  

For example,

```php
$clients = $ninja->clients->all([
    'balance' => 'lt:10', // get all clients with a balance less than 10
    'per_page' => 10, // return 10 results per page
    'page' => 2, // paginate to page 2
    'include' => 'documents', // include the documents relationship
]);

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

Create a new Client with a contact
```php
    $clients = $ninja->clients->create([
        'name' => 'Brand spanking new client',
        'contacts' => [
            [
                'first_name' => 'first',
                'last_name' => 'last',
                'send_email' => true,
                'email' => 'gi-joe@example.com',
            ],

        ]
    ]);
    
```

Update an existing client
```php
    $client = $ninja->clients->update("CLIENT_HASHED_ID",['name' => 'A client with a updated name']);

```

***Please Note***

When updating a client, you must always include the current contacts (array), if no contacts are included, the system will wipe the contacts from the client record.


Create an invoice
```php
    $invoice = $ninja->invoices->create([
        'client_id' => $client_hashed_id,
        'date' => '2022-10-31',
        'due_date' => '2022-12-01',
        'terms' => 'These are your invoice terms.',
        'footer' => 'Invoice footer text',
        'line_items' => [
            [
                'product_key' => 'some_product_key',
                'notes' => 'description',
                'quantity' => 1,
                'cost' => 10
            ],
            [                
                'product_key' => 'another_product_key',
                'notes' => 'description',
                'quantity' => 1,
                'cost' => 10
            ],
        ],
    ]);
```

When creating an invoice, you can perform actions on the invoice in a single call, for example, say you wish to create an invoice and also apply a payment to the invoice:

```php
    $invoice = $ninja->invoices->create([
        'client_id' => $client_hashed_id,
        'date' => '2022-10-31',
        'due_date' => '2022-12-01',
        'terms' => 'These are your invoice terms.',
        'footer' => 'Invoice footer text',
        'line_items' => [
            [
                'product_key' => 'some_product_key',
                'notes' => 'description',
                'quantity' => 1,
                'cost' => 10
            ],
            [                
                'product_key' => 'another_product_key',
                'notes' => 'description',
                'quantity' => 1,
                'cost' => 10
            ],
        ],
    ], 
    ['paid' => true] //the second parameter in this method is an array of actions ie paid,mark_sent_send_email,auto_bill
);
```
Or if you wish to apply a partial payment

```php
    $invoice = $ninja->invoices->create(['client_id'=> 'CLIENT_HASHED_ID'], ['amount_paid' => 10]);
```

Or you may want to automatically send and charge the invoice **note requires a payment method on file**
```php
    $invoice = $ninja->invoices->create(['client_id'=> 'CLIENT_HASHED_ID'], ['auto_bill' => true, 'send_email' => true]);
```


### Bulk actions

You can perform bulk actions against one or many entities. For example if you wish to batch archive a range of invoice you would do

```php
  $bulk = $ninja->invoices->archive(["hash_1","hash_2"]);
```

You can access the raw bulk method using the following:

```php  
$bulk = $ninja->invoices->bulk("archive", ["hash_1","hash_2"]);
```

If you wanted to download a invoice PDF
```php
    $pdf = $ninja->invoices->bulk("download", ["hash_1"]);
```

The following are a list of available bulk actions for invoices:

+ mark_sent
+ download
+ restore
+ archive
+ delete
+ paid
+ clone_to_quote
+ clone_to_invoice
+ cancel
+ reverse
+ email

For more examples of what can be achieved with the SDK, please inspect the tests folder in this repository. If you need clarity or more explicit examples of how to use the SDK, please create a issue.