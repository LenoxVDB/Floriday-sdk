<?php

namespace Lennord\FloridaySdk\Http\Modules;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

/**
 * Module responsible for obtaining OAuth access tokens from Floriday.
 *
 * Exposes a method to request a new access token using the client credentials
 * grant against the configured OAuth endpoint.
 */
class Oauth
{
    /**
     * Request a new OAuth access token from Floriday.
     *
     * Uses the client credentials grant with values from configuration.
     *
     * @return Response The HTTP response returned by Floriday (JSON with token fields on success).
     *
     * @throws RequestException When the response has a client or server error status.
     * @throws ConnectionException When the request cannot reach the server.
     */
    public function fetchToken(): Response
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->asForm()->post(config('floridaysdk.oauth_url'), [
            'client_id' => config('floridaysdk.client'),
            'client_secret' => config('floridaysdk.secret'),
            'scope' => config('floridaysdk.scope'),
            'grant_type' => 'client_credentials',
        ]);

        $response->throw();

        return $response;
    }
}
