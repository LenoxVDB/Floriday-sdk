<?php

namespace Lennord\FloridaySdk\Resources;

use Lennord\FloridaySdk\Resources\Base\BaseResource;
use Lennord\FloridaySdk\Resources\FulfillmentOrder\CreateRequest;
use Saloon\Http\Response;

class FulfillmentOrderResource extends BaseResource
{
    /**
     * Creates a new fulfillment order.
     */
    public function create(array $payload): Response
    {
        return $this->connector->withAuthorization()->send(new CreateRequest($payload));
    }
}
