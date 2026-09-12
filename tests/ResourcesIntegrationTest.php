<?php

declare(strict_types=1);

namespace Lennord\FloridaySdk\Tests;

use Illuminate\Support\Facades\Cache;
use Lennord\FloridaySdk\FloridayConnector;
use Lennord\FloridaySdk\Resources\Batch\CreateRequest;
use Lennord\FloridaySdk\Resources\Identities\GetRequest as IdGetRequest;
use Lennord\FloridaySdk\Resources\Token\GetRequest as TokenGetRequest;
use PHPUnit\Framework\Attributes\Test;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

final class ResourcesIntegrationTest extends TestCase
{
    #[Test]
    public function resources_apply_authorization_and_send_requests(): void
    {
        Cache::flush();

        $sdk = app(FloridayConnector::class);

        $mock = new MockClient([
            // First call should be the token fetch because of HasAuthToken
            GetRequest::class => MockResponse::make(['access_token' => 'token-1']),
            GetRequest::class => function ($pendingRequest) {
                // Authorization header should be present (Bearer token-1)
                $auth = $pendingRequest->headers()->get('Authorization');
                $this->assertSame('Bearer token-1', $auth);
                return MockResponse::make(['id' => 1]);
            },
            GetRequest::class => function ($pendingRequest) {
                // Ensure default Accept header from connector
                $this->assertSame('application/json', $pendingRequest->headers()->get('Accept'));
                return MockResponse::make([['id' => 10]]);
            },
            function ($pendingRequest) {
                // Warehouse true should add query flag
                $this->assertSame('/warehouses', parse_url($pendingRequest->getUrl(), PHP_URL_PATH));
                $this->assertSame('true', $pendingRequest->query()->get('excludeExternalWarehouses'));
                return MockResponse::make([['id' => 50]]);
            },
            CreateRequest::class => function ($pendingRequest) {
                $this->assertSame('POST', $pendingRequest->getMethod()->value);
                $this->assertSame('/batches', parse_url($pendingRequest->getUrl(), PHP_URL_PATH));
                $this->assertSame(['foo' => 'bar'], $pendingRequest->body()->all());
                return MockResponse::make(['created' => true], 201);
            },
        ]);

        // Attach the mock client to the connector so internal token requests are also mocked
        $sdk->withMockClient($mock);

        $sdk->identity()->get();
        $sdk->trade()->index();
        $sdk->warehouse()->index(excludeExternal: true);
        $sdk->batch()->withApiKey('key-1')->create(['foo' => 'bar']);

        // 1 token + 4 resource requests
        $mock->assertSentCount(5);
    }
}
