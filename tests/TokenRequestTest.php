<?php

declare(strict_types=1);

namespace Lennord\FloridaySdk\Tests;

use Lennord\FloridaySdk\FloridayConnector;
use Lennord\FloridaySdk\Resources\Token\TokenRequest;
use PHPUnit\Framework\Attributes\Test;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

final class TokenRequestTest extends TestCase
{
    #[Test]
    public function token_request_uses_oauth_url_and_form(): void
    {
        $request = new TokenRequest();

        $this->assertSame('https://login.test/oauth/token', $request->resolveEndpoint());

        $sdk = app(FloridayConnector::class);

        $mock = new MockClient([
            TokenRequest::class => function ($pendingRequest) {
                // Assert method and headers/body
                $this->assertSame('POST', $pendingRequest->getMethod()->value);
                $this->assertSame('application/x-www-form-urlencoded', $pendingRequest->headers()->get('Content-Type'));
                $this->assertSame('application/json', $pendingRequest->headers()->get('Accept'));
                $this->assertSame('client-id', $pendingRequest->body()->all()['client_id'] ?? null);
                $this->assertSame('client-secret', $pendingRequest->body()->all()['client_secret'] ?? null);
                $this->assertSame('scope-a scope-b', $pendingRequest->body()->all()['scope'] ?? null);
                return MockResponse::make([
                    'access_token' => 'abc123',
                ], 200, ['Content-Type' => 'application/json']);
            },
        ]);

        $sdk->withMockClient($mock);
        $response = $sdk->send($request);

        $this->assertSame('abc123', $response->json('access_token'));
        $mock->assertSent(TokenRequest::class);
    }
}
