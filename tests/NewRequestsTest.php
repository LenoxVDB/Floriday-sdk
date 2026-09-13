<?php

declare(strict_types=1);

namespace Lennord\FloridaySdk\Tests;

use Illuminate\Support\Str;
use Lennord\FloridaySdk\FloridayConnector;
use Lennord\FloridaySdk\Resources\Batch\CreateRequest as BatchCreateRequest;
use Lennord\FloridaySdk\Resources\Batch\GetRequest as BatchGetRequest;
use Lennord\FloridaySdk\Resources\Batch\PriceRequest as BatchPriceRequest;
use Lennord\FloridaySdk\Resources\DeliveryOrder\CreateRequest as DeliveryCreateRequest;
use Lennord\FloridaySdk\Resources\FulfillmentOrder\CreateRequest as FulfillmentCreateRequest;
use Lennord\FloridaySdk\Resources\Identities\GetRequest as IdentitiesGetRequest;
use Lennord\FloridaySdk\Resources\TradeItem\GetRequest as TradeGetRequest;
use Lennord\FloridaySdk\Resources\Warehouse\GetRequest as WarehouseGetRequest;
use PHPUnit\Framework\Attributes\Test;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

final class NewRequestsTest extends TestCase
{
    #[Test]
    public function batch_create_get_price_requests_behave_as_expected(): void
    {
        $sdk = app(FloridayConnector::class);

        $mock = new MockClient([
            // Token
            \Lennord\FloridaySdk\Resources\Token\GetRequest::class => MockResponse::make(['access_token' => 'tok-1']),
            BatchCreateRequest::class => function ($pending) {
                $this->assertSame('POST', $pending->getMethod()->value);
                $this->assertSame('/batches', parse_url($pending->getUrl(), PHP_URL_PATH));
                $this->assertSame(['a' => 1], $pending->body()->all());
                return MockResponse::make(['id' => 'b1'], 201);
            },
            BatchGetRequest::class => function ($pending) {
                $this->assertSame('GET', $pending->getMethod()->value);
                $this->assertSame('/batches/xyz', parse_url($pending->getUrl(), PHP_URL_PATH));
                return MockResponse::make(['id' => 'xyz']);
            },
            BatchPriceRequest::class => function ($pending) {
                $this->assertSame('PUT', $pending->getMethod()->value);
                $this->assertSame('/batches/xyz/base-supply', parse_url($pending->getUrl(), PHP_URL_PATH));
                $this->assertSame(['price' => 12.34], $pending->body()->all());
                return MockResponse::make(['ok' => true]);
            },
        ]);

        $sdk->withMockClient($mock);

        // Use resources to ensure withAuthorization is exercised
        $sdk->batch()->create(['a' => 1]);
        $sdk->batch()->get('xyz');
        $sdk->batch()->price(['price' => 12.34], 'xyz');

        $mock->assertSentCount(4);
    }

    #[Test]
    public function trade_and_identities_get_requests_endpoints(): void
    {
        $sdk = app(FloridayConnector::class);

        $mock = new MockClient([
            \Lennord\FloridaySdk\Resources\Token\GetRequest::class => MockResponse::make(['access_token' => 'tok-2']),
            TradeGetRequest::class => function ($p) {
                $this->assertSame('/trade-items', parse_url($p->getUrl(), PHP_URL_PATH));
                return MockResponse::make([]);
            },
            IdentitiesGetRequest::class => function ($p) {
                $this->assertSame('/identities', parse_url($p->getUrl(), PHP_URL_PATH));
                return MockResponse::make([]);
            },
        ]);

        $sdk->withMockClient($mock);

        $sdk->trade()->index();
        $sdk->identity()->get();

        $mock->assertSentCount(3);
    }

    #[Test]
    public function warehouse_get_request_query_flag_true_and_false(): void
    {
        $sdk = app(FloridayConnector::class);

        $mock = new MockClient([
            \Lennord\FloridaySdk\Resources\Token\GetRequest::class => MockResponse::make(['access_token' => 'tok-3']),
            function ($p) {
                $this->assertSame('/warehouses', parse_url($p->getUrl(), PHP_URL_PATH));
                $this->assertSame('true', $p->query()->get('excludeExternalWarehouses'));
                return MockResponse::make([]);
            },
            function ($p) {
                $this->assertSame('/warehouses', parse_url($p->getUrl(), PHP_URL_PATH));
                $this->assertSame('false', $p->query()->get('excludeExternalWarehouses'));
                return MockResponse::make([]);
            },
        ]);

        $sdk->withMockClient($mock);

        $sdk->warehouse()->index(true);
        $sdk->warehouse()->index(false);

        $mock->assertSentCount(3);
    }

    #[Test]
    public function fulfillment_and_delivery_create_requests(): void
    {
        $sdk = app(FloridayConnector::class);

        // Freeze UUID generation so the delivery endpoint is predictable
        $uuid = '01890a0d-bd5b-7a0f-8f1a-0a0b0c0d0e0f';
        Str::createUuidsUsing(fn () => Str::of($uuid));

        $mock = new MockClient([
            \Lennord\FloridaySdk\Resources\Token\GetRequest::class => MockResponse::make(['access_token' => 'tok-4']),
            FulfillmentCreateRequest::class => function ($p) {
                $this->assertSame('POST', $p->getMethod()->value);
                $this->assertSame('/fulfillment-orders', parse_url($p->getUrl(), PHP_URL_PATH));
                $this->assertSame(['x' => 'y'], $p->body()->all());
                return MockResponse::make(['ok' => 1], 201);
            },
            DeliveryCreateRequest::class => function ($p) use ($uuid) {
                $this->assertSame('POST', $p->getMethod()->value);
                $this->assertSame('/delivery-orders/' . $uuid . '/goods-movement', parse_url($p->getUrl(), PHP_URL_PATH));
                $this->assertSame(['d' => 2], $p->body()->all());
                return MockResponse::make(['ok' => 1], 201);
            },
        ]);

        $sdk->withMockClient($mock);

        $sdk->fulfillment()->create(['x' => 'y']);
        $sdk->delivery()->create(['d' => 2]);

        // Restore UUID factory
        Str::createUuidsUsing(null);

        $mock->assertSentCount(3);
    }
}
