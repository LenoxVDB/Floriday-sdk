<?php

namespace Lennord\FloridaySdk\Http\Modules;

use Illuminate\Http\Client\Response;
use Lennord\FloridaySdk\FloridaySdk;

/**
 * Module for retrieving warehouse information from Floriday.
 *
 * Provides access to the `/warehouses` endpoint.
 */
class Warehouses
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
     * Retrieve the list of warehouses from Floriday.
     *
     * Sends a GET request to the `/warehouses` endpoint. You can optionally
     * exclude external warehouses by passing `$excludeExternal = true`.
     *
     * @param bool $excludeExternal Whether to exclude external warehouses.
     *
     * @return Response The HTTP response from Floriday.
     *
     * @throws \Illuminate\Http\Client\ConnectionException If the request cannot reach the server.
     * @throws \Illuminate\Http\Client\RequestException If the response indicates a client/server error.
     */
    public function get(bool $excludeExternal = false): Response
    {
        return $this->sdk->http->get("/warehouses?excludeExternalWarehouses=$excludeExternal");
    }
}
