<?php

namespace Lennord\FloridaySdk\Http\Modules;

use Lennord\FloridaySdk\FloridaySdk;
use Illuminate\Http\Client\Response;

/**
 * Module for working with Trade Items in Floriday.
 *
 * Provides access to the `/trade-items` endpoint.
 */
class TradeItems
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
     * Retrieve all trade items from Floriday.
     *
     * Sends a GET request to the `/trade-items` endpoint.
     *
     * @return Response The HTTP response from Floriday.
     *
     * @throws \Illuminate\Http\Client\RequestException If the response indicates a client/server error.
     * @throws \Illuminate\Http\Client\ConnectionException If the request cannot reach the server.
     */
    public function getAll(): Response
    {
        return $this->sdk->http->get('/trade-items');
    }
}
