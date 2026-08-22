<?php

namespace Lennord\FloridaySdk\Tests;

use Illuminate\Support\Facades\Config;
use Lennord\FloridaySdk\Facades\FloridaySdk as FloridayFacade;
use Lennord\FloridaySdk\FloridaySdk;
use Lennord\FloridaySdk\FloridaySdkServiceProvider;

class ServiceProviderAndFacadeTest extends TestCase
{
    public function test_loads_the_package_service_provider_and_config(): void
    {
        $providers = array_keys(app()->getLoadedProviders());
        $this->assertContains(FloridaySdkServiceProvider::class, $providers);

        // From TestCase setup
        $this->assertSame('https://api.test', Config::get('floriday-sdk.base_api_url'));
    }

    public function test_facade_resolves_a_bound_sdk_instance(): void
    {
        $sdk = new FloridaySdk($this->fakeCredentials('fac', 'ade'));

        app()->instance(FloridaySdk::class, $sdk);

        // Accessing the facade should resolve our instance
        $resolved = FloridayFacade::getFacadeRoot();

        $this->assertSame($sdk, $resolved);
        $this->assertInstanceOf(FloridaySdk::class, $resolved);
    }
}
