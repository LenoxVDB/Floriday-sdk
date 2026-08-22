<?php

namespace Lennord\FloridaySdk\Http\Modules;

use Illuminate\Http\Client\Response;
use Lennord\FloridaySdk\FloridaySdk;

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
     * Retrieves the identity of a customer from the Floriday API.
     *
     * This method sends a GET request to the `/identities` endpoint of the
     * Floriday API and returns the response as an associative array.
     *
     * @return array The response data from the API in JSON-decoded format.
     * @throws \Illuminate\Http\Client\RequestException If the request fails.
     * @throws ConnectionException If there is a connection-related issue.
     */
    public function get(): Response
    {
        return $this->sdk->http->get('/identities');
    }
}
