<?php

namespace Lennord\FloridaySdk\Http\Modules;

use Illuminate\Http\Client\Response;
use Lennord\FloridaySdk\FloridaySdk;

/**
 * Module for retrieving account identity information from Floriday.
 *
 * Exposes a convenient SDK method to call the `/identities` endpoint.
 */
class Identities
{
    /**
     * The constructor.
     */
    public function __construct(
        private readonly FloridaySdk $sdk,
    )
    {
    }

    /**
     * Retrieve the identity of the authenticated account from Floriday.
     *
     * Sends a GET request to the `/identities` endpoint.
     *
     * @return Response The HTTP response from Floriday.
     *
     * @throws \Illuminate\Http\Client\RequestException If the response indicates a client/server error.
     * @throws \Illuminate\Http\Client\ConnectionException If the request cannot reach the server.
     */
    public function get(): Response
    {
        return $this->sdk->http->get('/identities');
    }
}
