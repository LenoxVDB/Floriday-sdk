<?php

namespace Lennord\FloridaySdk\Resources;

use Lennord\FloridaySdk\Resources\Base\BaseResource;
use Lennord\FloridaySdk\Resources\Warehouse\GetRequest;
use Saloon\Http\Response;

class WarehouseResource extends BaseResource
{
    /**
     * Retrieves a list of warehouses.
     */
    public function index(bool $excludeExternal = false): Response
    {
        return $this->connector->withAuthorization()->send(new GetRequest($excludeExternal));
    }
}
