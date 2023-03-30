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

namespace InvoiceNinja\Sdk\Models;

use InvoiceNinja\Sdk\Exceptions\ModelValidationException;

class BaseModel
{
	private array $validation_errors = [];

    public function validate(): bool | ModelValidationException
    {
        $this->resetValidationErrors();

        foreach($this->rules as $key => $rule)
        {

            switch($rule)
            {
                case 'required':
                    isset($this->{$key}) ?: $this->setValidationErrors(["error" => "{$this->model} - `{$key}` must be set", "code" => 422]);
                    break;
                case 'date':
                    isset($this->{$key}) ? $this->checkDate($key) : true;
                    break;
                default:
                	$this->setValidationErrors(["error" => "{$this->model} - Unknown validation rule `{$rule}` for property `{$key}`", "code" => 500]);

            }

        }

        if(count($this->getValidationErrors()) == 0)
        	return true;

        throw new ModelValidationException($this->getValidationErrors()[0]["error"] ,$this->getValidationErrors()[0]["code"]);
    }

    private function resetValidationErrors()
    {
        $this->validation_errors = [];

        return $this;
    }

    private function setValidationErrors($error): self
    {
        $this->validation_errors[] = $error;

        return $this;
    }

    public function getValidationErrors(): array
    {
        return $this->validation_errors;
    }

    public function checkDate(string $date_field): bool
    {

        try {

            $parsed_date = \Carbon\Carbon::parse($this->{$date_field});
            $this->{$date_field} = $parsed_date->format('Y-m-d');

            return true;
        }
        catch(\Exception $e) {
            $this->setValidationErrors([$date_field => "Invalid date format for field {$date_field} - '{$this->{$date_field}}'"]);
            return false;
        }
    }





}