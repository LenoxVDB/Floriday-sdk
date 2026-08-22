<?php

namespace Lennord\FloridaySdk\Http\Modules;

use Lennord\FloridaySdk\FloridaySdk;
use Illuminate\Http\Client\Response;

/**
 * Module for interacting with Floriday Batch endpoints.
 *
 * Provides operations related to creating and managing batches.
 */
class Batch
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
     * Create a new batch in Floriday.
     *
     * Sends a POST request to the `/batches` endpoint with the provided payload.
     *
     * @param array $payload The payload to be sent in the request body.
     *
     * @return Response The HTTP response from Floriday.
     *
     * @throws \Illuminate\Http\Client\RequestException If the response indicates a client/server error.
     * @throws \Illuminate\Http\Client\ConnectionException If the request cannot reach the server.
     */
    public function create(array $payload): Response
    {
        return $this->sdk->http->post('/batches', [
            'json' => $payload
        ]);
    }
}
