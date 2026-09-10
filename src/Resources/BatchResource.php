<?php

namespace Lennord\FloridaySdk\Resources;

use Lennord\FloridaySdk\Resources\Base\BaseResource;
use Lennord\FloridaySdk\Resources\Batch\BatchRequest;
use Saloon\Http\Response;

class BatchResource extends BaseResource
{
    /**
     * Creates a batch.
     */
    public function create(array $payload): Response
    {
        return $this->connector->withAuthorization()->send(new BatchRequest($payload));
    }
}
