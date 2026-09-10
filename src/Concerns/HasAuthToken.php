<?php

namespace Lennord\FloridaySdk\Concerns;

use Illuminate\Support\Facades\Cache;
use Lennord\FloridaySdk\FloridayConnector;
use Saloon\Http\Auth\TokenAuthenticator;

trait HasAuthToken
{
    const string CACHE_KEY = 'floriday-auth-key';

    /**
     * Gets the bearer token from the cache.
     */
    private function getAuthToken(): string
    {
        if (Cache::has(self::CACHE_KEY)) {
            return Cache::get(self::CACHE_KEY);
        }

        return $this->fetchAuthToken();
    }

    /**
     * Add the bearer token on the request.
     */
    public function withAuthorization(): FloridayConnector
    {
        $token = $this->getAuthToken();

        return $this->authenticate(new TokenAuthenticator($token));
    }

    /**
     * Fetch a new token.
     */
    private function fetchAuthToken(): string
    {
        $response = $this->token()->get();

        $token = $response->json('access_token');

        Cache::put(self::CACHE_KEY, $token, 3000);

        return $token;
    }
}
