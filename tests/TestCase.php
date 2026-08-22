<?php

namespace Lennord\FloridaySdk\Tests;

use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase as Orchestra;
use Lennord\FloridaySdk\FloridaySdkServiceProvider;
use Lennord\FloridaySdk\Contracts\CredentialsProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [FloridaySdkServiceProvider::class];
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Sensible default config for tests
        Config::set('floriday-sdk.base_api_url', 'https://api.test');
        // Legacy keys used by Oauth module
        Config::set('floridaysdk.oauth_url', 'https://login.test/oauth/token');
        Config::set('floridaysdk.client', 'client-id');
        Config::set('floridaysdk.secret', 'client-secret');
        Config::set('floridaysdk.scope', 'scope');
    }

    protected function fakeCredentials(string $token = 'test-token', string $apiKey = 'api-key'): object
    {
        return new class($token, $apiKey) implements CredentialsProvider {
            public function __construct(private string $bearerToken, private string $apiKey) {}
            public function getBearerToken(): string { return $this->bearerToken; }
            public function getApiToken(): string { return $this->apiKey; }
        };
    }
}
