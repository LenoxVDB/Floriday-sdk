# lennord/floriday-sdk

[![Latest Version on Packagist](https://img.shields.io/packagist/v/lennord/floriday-sdk.svg?style=flat-square)](https://packagist.org/packages/lennord/floriday-sdk)
[![Total Downloads](https://img.shields.io/packagist/dt/lennord/floriday-sdk.svg?style=flat-square)](https://packagist.org/packages/lennord/floriday-sdk)

A Saloon-powered SDK for integrating with the Floriday platform from Laravel/PHP. It provides a small Saloon Connector plus Resources for common Floriday API endpoints (Token, Trade Items, Identities, Warehouses, Batches). The connector takes care of base URL, default headers, bearer auth, and consistent error handling.

What is Saloon? Saloon is a modern, type-safe PHP HTTP client. This package builds on Saloon 4 and follows its Connector/Request/Resource patterns so you can use the SDK exactly like any other Saloon integration.

## Requirements
- PHP ^8.4
- Laravel 11/12/13 (package is compatible with Illuminate Contracts)

## Installation
Install via Composer:

```bash
composer require lennord/floriday-sdk
```

Publish the config file (optional, but recommended):

```bash
php artisan vendor:publish --tag="floriday-sdk-config"
```

## Configuration
This package reads its settings from `config/floriday-sdk.php`, which in turn can be fed by environment variables:

```php
return [
    'base_url'  => env('FLORIDAY_API_URL', ''),
    'oauth_url' => env('FLORIDAY_API_OAUTH_URL', ''),
    'client'    => env('FLORIDAY_API_CLIENT_ID', ''),
    'secret'    => env('FLORIDAY_API_CLIENT_SECRET', ''),
    'scope'     => env('FLORIDAY_API_SCOPE', ''),
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

## Using the SDK with Saloon
The main entry point is the Saloon Connector `Lennord\FloridaySdk\FloridayConnector`. It exposes Resources for each endpoint:

- token(): `Lennord\FloridaySdk\Resources\TokenResource`
- identity(): `Lennord\FloridaySdk\Resources\IdentitiesResource`
- trade(): `Lennord\FloridaySdk\Resources\TradeItemResource`
- warehouse(): `Lennord\FloridaySdk\Resources\WarehouseResource`
- batch(): `Lennord\FloridaySdk\Resources\BatchResource`

Authentication:
- The connector ships with a trait `HasAuthToken` which will automatically fetch an access token using the Token endpoint and cache it in Laravel Cache. To send an authenticated request, call `withAuthorization()` on the connector before `send()` is executed. The resources in this SDK do that for you under the hood.
- Floriday also requires an `X-Api-Key` header for some endpoints. You can set it via `withApiKey('your-api-key')` on any resource before calling the method.

### Resolve the connector (Laravel container)
```php
use Lennord\FloridaySdk\FloridayConnector;

$floriday = app(FloridayConnector::class);
```

Or use the provided Facade:
```php
use Lennord\FloridaySdk\Facades\Floriday as FloridayFacade;

// Example: FloridayFacade::identity()->get()->json();
```

### Get an OAuth token (Saloon Response)
```php
use Lennord\FloridaySdk\FloridayConnector;

$floriday = app(FloridayConnector::class);

$response = $floriday->token()->get();
$token = $response->json('access_token');
```

### Identities
```php
$identity = $floriday->identity()->get()->json();
```

### Trade items
```php
$items = $floriday->trade()->index()->json();
```

### Warehouses (optionally exclude external)
```php
$warehouses = $floriday->warehouse()->index(excludeExternal: true)->json();
```

### Batches (create)
```php
$payload = [
    // ... batch fields ...
];
$batch = $floriday->batch()->withApiKey('your-api-key')->create($payload)->json();
```

Notes on headers & auth:
- Default headers include `Accept: application/json` and `Content-Type: application/json`.
- `withAuthorization()` is applied within each resource method so you generally don’t need to call it yourself.
- If you need to set the `X-Api-Key` header globally for a workflow, you can call `withApiKey()` once on any resource before making multiple calls; it adds the header to the underlying connector for subsequent requests in the same instance.

## Error handling (Saloon)
This connector uses Saloon’s `AlwaysThrowOnErrors` plugin, which throws exceptions for 4xx/5xx responses. Catch Saloon’s request exceptions when needed, for example:

```php
use Saloon\Exceptions\Request\RequestException;

try {
    $items = $floriday->trade()->index()->json();
} catch (RequestException $e) {
    // Inspect $e->getResponse() if needed
}
```

## Testing
This project uses PHPUnit.

- Run tests:
```bash
composer test
```

- Run with coverage (requires Xdebug or PCOV):
```bash
composer test-coverage
```
If you see “No code coverage driver is available”, enable Xdebug (`xdebug.mode=coverage`) or PCOV in your PHP CLI.

## Changelog
Please see [CHANGELOG](CHANGELOG.md) for recent changes.

## Security
If you discover any security related issues, please open an issue or contact the maintainer directly.

## License
The MIT License (MIT). Please see [LICENSE.md](LICENSE.md) for details.

## Credits
- [LenoxVDB](https://github.com/LenoxVDB)
- [All Contributors](../../contributors)
