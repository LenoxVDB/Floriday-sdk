<?php

declare(strict_types=1);

namespace Lennord\FloridaySdk\Tests;

use Illuminate\Support\Facades\Cache;
use Lennord\FloridaySdk\FloridayConnector;
use Lennord\FloridaySdk\Resources\Token\TokenRequest;
use Lennord\FloridaySdk\Resources\TradeItem\TradeItemRequest;
use PHPUnit\Framework\Attributes\Test;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

final class HasAuthTokenTest extends TestCase
{
    #[Test]
    public function has_auth_token_caches_token_and_reuses_it(): void
    {
        Cache::flush();

        $sdk = app(FloridayConnector::class);

        $mock = new MockClient([
            TokenRequest::class => MockResponse::make(['access_token' => 'tkn-cache']),
            TradeItemRequest::class => function ($pendingRequest) {
                $this->assertSame('Bearer tkn-cache', $pendingRequest->headers()->get('Authorization'));
                return MockResponse::make(['ok' => true]);
            },
            // Second trade call should NOT trigger another token request due to cache
            TradeItemRequest::class => MockResponse::make(['ok' => true]),
        ]);

        // Attach mock to the connector so internal token request is mocked
        $sdk->withMockClient($mock);

        $sdk->trade()->index();
        $sdk->trade()->index();

        // Only one token call should have been made
        $mock->assertSentCount(3); // 1 token + 2 trade
    }
}
