<?php

namespace Lennord\FloridaySdk\Resources;

use Lennord\FloridaySdk\Resources\Base\BaseResource;
use Lennord\FloridaySdk\Resources\Batch\CreateRequest;
use Lennord\FloridaySdk\Resources\Batch\GetRequest;
use Lennord\FloridaySdk\Resources\Batch\PriceRequest;
use Saloon\Http\Response;

class BatchResource extends BaseResource
{
    /**
     * Creates a batch.
     */
    public function create(array $payload): Response
    {
        return $this->connector->withAuthorization()->send(new CreateRequest($payload));
    }

    /**
     * Retrieves a batch.
     */
    public function get(string $batchId): Response
    {
        return $this->connector->withAuthorization()->send(new GetRequest($batchId));
    }

    /**
     * Adds a price on an exising batch.
     */
    public function price(array $payload, string $batchId): Response
    {
        return $this->connector->withAuthorization()->send(new PriceRequest($payload, $batchId));
    }
}
