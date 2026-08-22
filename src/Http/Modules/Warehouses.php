<?php

namespace Lennord\FloridaySdk\Http\Modules;

use Illuminate\Http\Client\Response;
use Lennord\FloridaySdk\FloridaySdk;

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
     * Retrieves a list of warehouses from the Floriday API.
     *
     * This method sends a GET request to the `/warehouses` endpoint of the Floriday API,
     * including the option to exclude external warehouses by default.
     *
     * @return array An array containing the list of warehouses retrieved from the API.
     *
     * @throws \Illuminate\Http\Client\ConnectionException If there is a connection error.
     * @throws \Exception If the API response indicates an error.
     */
    public function get(bool $excludeExternal = false): Response
    {
        return $this->sdk->http->get("/warehouses?excludeExternalWarehouses=$excludeExternal");
    }
}
