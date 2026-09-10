<?php

namespace Lennord\FloridaySdk\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Lennord\FloridaySdk\FloridaySdkServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            FloridaySdkServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Configure default Floriday SDK config for tests
        config()->set('floriday-sdk.base_url', 'https://api.test');
        config()->set('floriday-sdk.oauth_url', 'https://login.test/oauth/token');
        config()->set('floriday-sdk.client', 'client-id');
        config()->set('floriday-sdk.secret', 'client-secret');
        config()->set('floriday-sdk.scope', 'scope-a scope-b');

        // Use array cache to avoid external dependencies
        config()->set('cache.default', 'array');
    }
}
