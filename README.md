# lennord/floriday-sdk

[![Latest Version on Packagist](https://img.shields.io/packagist/v/lennord/floriday-sdk.svg?style=flat-square)](https://packagist.org/packages/lennord/floriday-sdk)
[![Total Downloads](https://img.shields.io/packagist/dt/lennord/floriday-sdk.svg?style=flat-square)](https://packagist.org/packages/lennord/floriday-sdk)

A developer-friendly SDK for integrating with the Floriday platform from Laravel/PHP. It provides a thin, expressive wrapper around common Floriday API endpoints (OAuth, Trade Items, Identities, Warehouses, Batches) and a small HTTP client that handles base URL, authentication headers, and error handling.

### About Floriday
Floriday is a digital platform for the international flower and plant industry that connects growers, buyers, and other businesses in the horticultural supply chain. Besides providing an online environment for trading, Floriday offers APIs that allow companies to connect their own software and business systems directly to the platform. These APIs make it possible to automate processes that would otherwise require manual work. For example, businesses can use Floriday’s APIs to exchange product information, manage offers, submit and process orders, and retrieve information about transactions. This allows companies to integrate Floriday with their own ERP, webshop, order-management, or logistics systems. Instead of employees entering the same information into multiple systems, data can be transferred automatically between the company’s software and Floriday. The API is therefore particularly useful for businesses that handle large numbers of flowers, plants, products, or orders and need reliable data exchange. Integration with Floriday can also help businesses improve efficiency, reduce errors, and keep information more consistent across different systems. Developers can build integrations around Floriday’s available API services while following its authentication, data formats, and technical requirements. Depending on the specific API functionality being used, companies need to understand how requests, responses, product data, orders, and other resources are structured. Overall, Floriday’s API turns the platform from simply an online trading environment into a system that can be integrated into a company’s wider digital infrastructure. This enables more automated, scalable, and efficient processes throughout the flower and plant supply chain.

## Requirements
- PHP ^8.4
- Laravel 9/10/11/12/13 (via Illuminate Contracts compatibility)

## Installation
Install via Composer:

```bash
composer require lennord/floriday-sdk
```

Publish the config file:

```bash
php artisan vendor:publish --tag="floriday-sdk-config"
```

## Configuration
This package reads its settings from `config/floriday-sdk.php`, which in turn can be fed by environment variables:

```php
return [
    'base_api_url' => env('FLORIDAY_API_URL', ''),
    'oauth_url'    => env('FLORIDAY_API_OAUTH_URL', ''),
    'client'       => env('FLORIDAY_API_CLIENT_ID', ''),
    'secret'       => env('FLORIDAY_API_CLIENT_SECRET', ''),
    'scope'        => env('FLORIDAY_API_SCOPE', ''),
];
```

Set these in your `.env`:

```
FLORIDAY_API_URL=https://api.floriday.io
FLORIDAY_API_OAUTH_URL=https://login.floriday.io/oauth/token
FLORIDAY_API_CLIENT_ID=your-client-id
FLORIDAY_API_CLIENT_SECRET=your-client-secret
FLORIDAY_API_SCOPE=your-scope
```

## Quick start
1) Implement `Lennord\FloridaySdk\Contracts\CredentialsProvider` so the SDK can obtain an access token and API key when sending requests.

```php
use Lennord\FloridaySdk\Contracts\CredentialsProvider;

class MyCredentials implements CredentialsProvider
{
    public function __construct(
        private string $bearerToken,
        private string $apiKey,
    ) {}

    public function getBearerToken(): string { return $this->bearerToken; }
    public function getApiToken(): string { return $this->apiKey; }
}
```

2) Create the SDK instance and call the modules:

```php
use Lennord\FloridaySdk\FloridaySdk;

$sdk = new FloridaySdk(new MyCredentials($accessToken, $apiKey));

// Example calls
$items = $sdk->tradeItems->getAll()->json();
$identity = $sdk->identity->get()->json();
$warehouses = $sdk->warehouse->get()->json();
```

## Usage examples

### OAuth
Request a new access token using the client credentials grant:

```php
$response = $sdk->oauth->fetchToken();
$token = $response->json('access_token');
```

### Trade items
```php
$response = $sdk->tradeItems->getAll();
$items = $response->json();
```

### Identities
```php
$response = $sdk->identity->get();
$identity = $response->json();
```

### Warehouses
```php
$response = $sdk->warehouse->get(excludeExternal: true);
$warehouses = $response->json();
```

### Batches
```php
$payload = [
    // ... batch fields ...
];
$response = $sdk->batch->create($payload);
$batch = $response->json();
```

## Console: GenerateFloridayTokenCommand (abstract)
This package ships with an abstract base command `Lennord\FloridaySdk\Commands\GenerateFloridayTokenCommand` that obtains an OAuth access token from Floriday and makes it available via the protected `$this->token` property. You extend this abstract command to decide what to do with the token (for example, print it, store it in the database, cache, or configuration store).

- What it does
  - Calls `$sdk->oauth->fetchToken()` and extracts the `access_token`.
  - If a token is returned, it sets `$this->token` and then calls your implementation of `handleToken()`.
  - Returns a standard CLI exit code (`SUCCESS`/`FAILURE`).

- How to use it
  1) Create your concrete command by extending the abstract base:

```php
<?php

namespace App\Console\Commands;

use Lennord\FloridaySdk\Commands\GenerateFloridayTokenCommand;

class FloridayTokenMakeCommand extends GenerateFloridayTokenCommand
{
    protected $signature = 'floriday:token:make';
    protected $description = 'Generate and display/store a Floriday access token';

    protected function handleToken(): int
    {
        // `$this->token` now contains the generated access token
        $this->info('Floriday token: ' . $this->token);

        // Example: store in cache or database
        // cache()->put('floriday.access_token', $this->token, now()->addHour());

        return self::SUCCESS;
    }
}
```

  2) Registration (Laravel 11–13): In modern Laravel you typically don't need to manually register the command if it lives in `app/Console/Commands` and you keep the default Kernel. The Kernel already loads that directory. If you've customized it, make sure your Kernel contains a `commands()` method like this:

```php
protected function commands(): void
{
    $this->load(__DIR__.'/Commands');

    require base_path('routes/console.php');
}
```

  3) Run it:

```bash
php artisan floriday:token:make
```

Notes:
- The command relies on dependency injection for `Lennord\FloridaySdk\FloridaySdk`, so ensure your configuration and credentials are set.
- Implement `handleToken()` to persist the token wherever your application expects it.

## Error handling
All HTTP requests are made using Laravel's HTTP client and will call `$response->throw()` under the hood. Catch `\Illuminate\Http\Client\RequestException` for 4xx/5xx responses and `\Illuminate\Http\Client\ConnectionException` for connectivity issues.

## Testing
```bash
composer test
```

## Changelog
Please see [CHANGELOG](CHANGELOG.md) for recent changes.

## Security
If you discover any security related issues, please open an issue or contact the maintainer directly.

## License
The MIT License (MIT). Please see [LICENSE.md](LICENSE.md) for details.

## Credits
- [LenoxVDB](https://github.com/LenoxVDB)
- [All Contributors](../../contributors)
