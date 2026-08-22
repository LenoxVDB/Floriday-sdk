<?php

namespace Lennord\FloridaySdk\Tests;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Lennord\FloridaySdk\Http\HttpClient;

class HttpClientTest extends TestCase
{
    public function test_sends_get_requests_with_sdk_headers_and_base_url(): void
    {
        $client = new HttpClient($this->fakeCredentials('abc', 'xyz'));

        Http::fake([
            'https://api.test/identities' => Http::response(['ok' => true], 200, ['X-Debug' => '1'])
        ]);

        $response = $client->get('/identities');

        $this->assertTrue($response->json('ok'));

        Http::assertSent(function ($request) {
            return $request->method() === 'GET'
                && $request->url() === 'https://api.test/identities'
                && $request->hasHeader('Authorization', 'Bearer abc')
                && $request->hasHeader('X-Api-Key', 'xyz')
                && $request->hasHeader('Content-Type', 'application/json');
        });
    }

    public function test_sends_post_requests_with_json_body(): void
    {
        $client = new HttpClient($this->fakeCredentials('tok', 'key'));

        Http::fake([
            'https://api.test/batches' => Http::response(['created' => 1], 201)
        ]);

        $payload = ['a' => 1, 'b' => 2];

        $response = $client->post('/batches', ['json' => $payload]);

        $this->assertSame(201, $response->status());

        Http::assertSent(function ($request) use ($payload) {
            return $request->method() === 'POST'
                && $request->url() === 'https://api.test/batches'
                && $request['a'] === 1
                && $request['b'] === 2;
        });
    }

    public function test_throws_request_exception_on_4xx_5xx(): void
    {
        $client = new HttpClient($this->fakeCredentials());

        Http::fake([
            'https://api.test/boom' => Http::response(['error' => 'bad'], 500)
        ]);

        $this->expectException(RequestException::class);
        $client->get('/boom');
    }

    public function test_bubbles_connection_exception_from_underlying_http_call(): void
    {
        $client = new HttpClient($this->fakeCredentials());

        Http::fake(function () {
            throw new ConnectionException('no network');
        });

        $this->expectException(ConnectionException::class);
        $client->request('GET', '/any');
    }
}
