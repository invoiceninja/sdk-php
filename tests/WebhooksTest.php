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

namespace InvoiceNinja\Sdk\Tests;

use InvoiceNinja\Sdk\InvoiceNinja;
use InvoiceNinja\Sdk\Models\Webhook;
use PHPUnit\Framework\TestCase;

class WebhooksTest extends TestCase
{
    /**
     * @warning testWebhookDelete() will delete all webhook records containing this string.
     * So make sure its relatively unique, in order to avoid unwanted deletions.
     */
    public string $test_base_url = "https://example.com:443/api/invoiceninja-webhook/";

    public function testWebhooks()
    {
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);
    
        $webhooks = $ninja->webhooks->all();

        $this->assertIsArray($webhooks);

    }

    public function testWebhookPost()
    {
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $model = new Webhook();
        $model->event_id = Webhook::EVENT_CREATE_CLIENT;
        $model->target_url = $this->test_base_url;

        $this->assertTrue($model->validate());

        $webhooks = $ninja->webhooks->create((array) $model);

        $this->assertIsArray($webhooks);

    }

    public function testWebhookPut()
    {
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $webhook = $ninja->webhooks->create([
            'event_id' => Webhook::EVENT_UPDATE_CLIENT,
            'target_url' => $this->test_base_url
        ]);

        $attributes = ['target_url' => $this->test_base_url.'-updated-url'];
        $webhook_updated = array_merge($webhook['data'], $attributes);

        $webhooks = $ninja->webhooks->update($webhook['data']['id'], $webhook_updated);

        $this->assertIsArray($webhooks);

        $this->assertContains($webhook_updated, $webhooks);
        
    }

    public function testWebhookGet()
    {
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $webhook_created = $ninja->webhooks->create([
            'event_id' => Webhook::EVENT_CREATE_CLIENT,
            'target_url' => $this->test_base_url,
        ]);

        $this->assertIsArray($webhook_created);

        $webhook = $ninja->webhooks->get($webhook_created['data']['id']);

        $this->assertEquals($webhook_created['data']['id'], $webhook['data']['id']);

    }

    public function testWebhookDelete()
    {
        $ninja = new InvoiceNinja($_ENV['INVOICENINJA_TOKEN']);
        $ninja->setUrl($_ENV['INVOICENINJA_URL']);

        $ninja->webhooks->create([
            'event_id' => Webhook::EVENT_CREATE_CLIENT,
            'target_url' => $this->test_base_url
        ]);
        $webhooks = $ninja->webhooks->all(['target_url' => $this->test_base_url, 'status' => 'active']);

        $this->assertNotEmpty($webhooks['data']);

        $webhooks = $ninja->webhooks->delete(array_column($webhooks['data'], 'id'));

        $this->assertIsArray($webhooks);

        $webhooks = $ninja->webhooks->all(['target_url' => $this->test_base_url, 'status' => 'active']);

        $this->assertEmpty($webhooks['data']);
    }
}