<?php

namespace Lennord\FloridaySdk\Tests;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Lennord\FloridaySdk\FloridaySdk;
use Lennord\FloridaySdk\Http\HttpClient;
use Lennord\FloridaySdk\Http\Modules\Batch;
use Lennord\FloridaySdk\Http\Modules\Identities;
use Lennord\FloridaySdk\Http\Modules\Oauth;
use Lennord\FloridaySdk\Http\Modules\TradeItems;
use Lennord\FloridaySdk\Http\Modules\Warehouses;

class FloridaySdkTest extends TestCase
{
    public function test_wires_modules_and_http_client(): void
    {
        $sdk = new FloridaySdk($this->fakeCredentials());

        $this->assertInstanceOf(HttpClient::class, $sdk->http);
        $this->assertInstanceOf(Oauth::class, $sdk->oauth);
        $this->assertInstanceOf(Batch::class, $sdk->batch);
        $this->assertInstanceOf(TradeItems::class, $sdk->tradeItems);
        $this->assertInstanceOf(Identities::class, $sdk->identity);
        $this->assertInstanceOf(Warehouses::class, $sdk->warehouse);
    }

    public function test_uses_base_api_url_from_config_for_requests_made_via_modules(): void
    {
        Config::set('floriday-sdk.base_api_url', 'https://api.example');
        $sdk = new FloridaySdk($this->fakeCredentials('bear', 'key'));

        Http::fake([
            'https://api.example/identities' => Http::response(['id' => 1], 200)
        ]);

        $res = $sdk->identity->get();
        $this->assertSame(1, $res->json('id'));

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.example/identities'
                && $request->hasHeader('Authorization', 'Bearer bear')
                && $request->hasHeader('X-Api-Key', 'key');
        });
    }
}
