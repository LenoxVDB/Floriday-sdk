<?php

declare(strict_types=1);

namespace Lennord\FloridaySdk\Tests;

use Lennord\FloridaySdk\FloridayConnector;
use Lennord\FloridaySdk\Resources\TradeItem\GetRequest;
use PHPUnit\Framework\Attributes\Test;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

final class BaseResourceTest extends TestCase
{
    #[Test]
    public function base_resource_can_add_api_key_header(): void
    {
        $sdk = app(FloridayConnector::class);

        $mock = new MockClient([
            // Token request used by HasAuthToken
            \Lennord\FloridaySdk\Resources\Token\GetRequest::class => MockResponse::make(['access_token' => 'dummy-token']),
            GetRequest::class => function ($pendingRequest) {
                // Assert header is present on the connector/request
                $this->assertSame('my-api-key', $pendingRequest->headers()->get('X-Api-Key'));
                return MockResponse::make(['ok' => true]);
            },
        ]);

        $sdk->withMockClient($mock);
        $sdk->trade()->withApiKey('my-api-key')->index();

        $mock->assertSent(GetRequest::class);
    }
}
