<?php

namespace InvoiceNinja\Sdk\Tests\Unit;

use Carbon\Carbon;
use InvoiceNinja\Sdk\Exceptions\ModelValidationException;
use InvoiceNinja\Sdk\Models\BaseModel;
use InvoiceNinja\Sdk\Models\Client;
use InvoiceNinja\Sdk\Models\ClientContact;
use InvoiceNinja\Sdk\Models\ClientSettings;
use InvoiceNinja\Sdk\Models\Invoice;
use InvoiceNinja\Sdk\Models\InvoiceItem;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ModelsTest extends TestCase
{
    public function testContactsAndLineItemsAppendInOrder(): void
    {
        $first = new ClientContact();
        $first->email = 'first@example.test';
        $second = new ClientContact();
        $second->email = 'second@example.test';
        $client = new Client();
        $client->id = 'fixture';
        $client->name = 'SDK fixture';
        $client->client_settings = new ClientSettings();

        self::assertSame($client, $client->setContact($first)->setContact($second));
        self::assertSame([$first, $second], $client->getContacts());
        self::assertSame('first@example.test', json_decode(json_encode($client), true)['contacts'][0]['email']);

        $item = new InvoiceItem();
        $invoice = new Invoice();
        self::assertSame($invoice, $invoice->setClient($client));
        self::assertSame('fixture', $invoice->client_id);
        self::assertSame($invoice, $invoice->setClientId('replacement'));
        self::assertSame($invoice, $invoice->addLine($item)->addLine($item));
        self::assertSame([$item, $item], $invoice->line_items);
        self::assertTrue($invoice->validate());
    }

    public function testRequiredFieldsAndErrorsResetAfterCorrection(): void
    {
        $client = new Client();

        try {
            $client->save();
            self::fail('Missing client name must fail validation.');
        } catch (ModelValidationException $exception) {
            self::assertSame(422, $exception->getCode());
            self::assertStringContainsString('name', $exception->getMessage());
            self::assertCount(1, $client->getValidationErrors());
        }

        $client->name = 'Corrected';
        $client->save();
        self::assertSame([], $client->getValidationErrors());
    }

    #[DataProvider('dates')]
    public function testDateNormalization(string $input, string $expected): void
    {
        $invoice = (new Invoice())->setClientId('fixture');
        $invoice->date = $input;
        $invoice->due_date = $input;
        $invoice->partial_due_date = $input;

        self::assertTrue($invoice->validate());
        self::assertSame($expected, $invoice->date);
        self::assertSame($expected, $invoice->due_date);
        self::assertSame($expected, $invoice->partial_due_date);
    }

    public static function dates(): iterable
    {
        yield 'ISO date' => ['2026-11-02', '2026-11-02'];
        yield 'offset crossing UTC boundary' => ['2026-11-02T00:30:00+11:00', '2026-11-02'];
        yield 'negative offset' => ['2026-11-02T23:30:00-10:00', '2026-11-02'];
        yield 'Australian daylight saving' => ['2026-10-04 03:30:00 Australia/Sydney', '2026-10-04'];
        yield 'leap day' => ['2024-02-29', '2024-02-29'];
    }

    public function testNullAndEmptyDatesRetainCarbonSemantics(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-11-02 12:00:00 UTC'));

        try {
            $invoice = (new Invoice())->setClientId('fixture');
            self::assertTrue($invoice->validate());
            self::assertNull($invoice->date);
            $invoice->date = '';
            self::assertTrue($invoice->validate());
            self::assertSame('2026-11-02', $invoice->date);
        } finally {
            Carbon::setTestNow();
        }
    }

    public function testInvalidDateRaisesValidationExceptionAndCanBeCorrected(): void
    {
        $invoice = (new Invoice())->setClientId('fixture');
        $invoice->date = 'this-is-not-a-date';

        try {
            $invoice->validate();
            self::fail('Invalid dates must fail validation.');
        } catch (ModelValidationException $exception) {
            self::assertSame(422, $exception->getCode());
            self::assertStringContainsString('Invalid date format', $exception->getMessage());
        }

        $invoice->date = '2026-11-02';
        self::assertTrue($invoice->validate());
        self::assertSame([], $invoice->getValidationErrors());
    }

    public function testUnknownValidationRuleFailsExplicitly(): void
    {
        $model = new class extends BaseModel {
            protected string $model = 'Fixture';
            protected array $rules = ['name' => 'unsupported'];
        };

        try {
            $model->validate();
            self::fail('Unsupported rules must fail validation.');
        } catch (ModelValidationException $exception) {
            self::assertSame(500, $exception->getCode());
            self::assertStringContainsString('Unknown validation rule', $exception->getMessage());
        }
    }
}
