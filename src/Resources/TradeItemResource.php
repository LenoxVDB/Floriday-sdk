<?php

namespace Lennord\FloridaySdk\Resources;

use Lennord\FloridaySdk\Resources\Base\BaseResource;
use Lennord\FloridaySdk\Resources\TradeItem\GetRequest;
use Saloon\Http\Response;

class TradeItemResource extends BaseResource
{
    /**
     * Retrieves a list of trade items.
     */
    public function index(): Response
    {
        return $this->connector->withAuthorization()->send(new GetRequest());
    }
}
