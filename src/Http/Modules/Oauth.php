<?php

namespace Lennord\FloridaySdk\Http\Modules;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class Oauth
{
    /**
     * Fetches a new authentication token from the Floriday API.
     *
     * This method makes a GET request to the configured OAuth URL to retrieve
     * a new authentication token for API access.
     *
     * @return Response Returns a JSON response containing the authentication token.
     * If successful, returns the token data.
     * If failed, returns a 500 error with the error message.
     * @throws RequestException|ConnectionException
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
