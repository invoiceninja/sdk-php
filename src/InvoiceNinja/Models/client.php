<?php namespace InvoiceNinja\Models;

class Client extends RemoteModel
{
    public static $route = 'clients';

    public $contacts = [];

    public function __construct($email = '', $first_name = '', $last_name = '', $name = '')
    {
        $this->name = $name;

        $contact = new Contact();
        $contact->email = $email;
        $contact->first_name = $first_name;
        $contact->last_name = $last_name;

        $this->contacts[] = $contact;
    }
}
