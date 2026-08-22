<?php

namespace Lennord\FloridaySdk\Http\Modules;

use Lennord\FloridaySdk\FloridaySdk;
use Illuminate\Http\Client\Response;

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
     * Creates a new batch by making a POST request to the specified endpoint with the provided data.
     *
     * @param array $data The data to be sent in the request body.
     * @return Response
     */
    public function create(array $data): Response
    {
        return $this->sdk->http->post('/batches', [
            'json' => $data
        ]);
    }
}
