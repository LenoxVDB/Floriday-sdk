<?php

namespace Lennord\FloridaySdk\Resources;

use Lennord\FloridaySdk\Resources\Base\BaseResource;
use Lennord\FloridaySdk\Resources\DeliveryOrder\CreateRequest;
use Saloon\Http\Response;

class DeliveryOrderResource extends BaseResource
{
    /**
     * Creates a new delivery order.
     */
    public function create(array $payload): Response
    {
        return $this->connector->withAuthorization()->send(new CreateRequest($payload));
    }
}
