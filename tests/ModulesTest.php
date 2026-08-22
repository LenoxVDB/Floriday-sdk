<?php

namespace Lennord\FloridaySdk\Tests;

use Illuminate\Support\Facades\Http;
use Lennord\FloridaySdk\FloridaySdk;

class ModulesTest extends TestCase
{
    public function test_identities_get_hits_identities(): void
    {
        Http::fake([
            'https://api.test/identities' => Http::response(['who' => 'me'], 200)
        ]);

        $sdk = new FloridaySdk($this->fakeCredentials('t', 'k'));
        $res = $sdk->identity->get();

        $this->assertSame('me', $res->json('who'));

        Http::assertSent(fn ($req) => $req->method() === 'GET' && $req->url() === 'https://api.test/identities');
    }

    public function test_trade_items_get_all_hits_trade_items(): void
    {
        Http::fake([
            'https://api.test/trade-items' => Http::response([["id" => 1]], 200)
        ]);

        $sdk = new FloridaySdk($this->fakeCredentials());
        $res = $sdk->tradeItems->getAll();

        $this->assertSame(1, $res->json(0)['id']);

        Http::assertSent(fn ($req) => $req->method() === 'GET' && $req->url() === 'https://api.test/trade-items');
    }

    public function test_warehouses_get_toggles_exclude_external_false_by_default(): void
    {
        Http::fake([
            'https://api.test/warehouses?excludeExternalWarehouses=false' => Http::response([], 200)
        ]);

        $sdk = new FloridaySdk($this->fakeCredentials());
        $sdk->warehouse->get();

        Http::assertSent(fn ($req) => $req->url() === 'https://api.test/warehouses?excludeExternalWarehouses=false');
    }

    public function test_warehouses_get_can_set_exclude_external_true(): void
    {
        Http::fake([
            'https://api.test/warehouses?excludeExternalWarehouses=true' => Http::response([], 200)
        ]);

        $sdk = new FloridaySdk($this->fakeCredentials());
        $sdk->warehouse->get(true);

        Http::assertSent(fn ($req) => $req->url() === 'https://api.test/warehouses?excludeExternalWarehouses=true');
    }

    public function test_batch_create_posts_body_to_batches(): void
    {
        Http::fake([
            'https://api.test/batches' => Http::response(['id' => 10], 201)
        ]);

        $sdk = new FloridaySdk($this->fakeCredentials());
        $payload = ['x' => 1, 'y' => 2];
        $res = $sdk->batch->create($payload);

        $this->assertSame(201, $res->status());

        Http::assertSent(function ($req) use ($payload) {
            return $req->method() === 'POST'
                && $req->url() === 'https://api.test/batches'
                && $req['x'] === 1 && $req['y'] === 2;
        });
    }
}
