<?php

namespace Lennord\FloridaySdk\Resources;

use Lennord\FloridaySdk\Resources\Base\BaseResource;
use Lennord\FloridaySdk\Resources\Token\GetRequest;
use Saloon\Http\Response;

class TokenResource extends BaseResource
{
    /**
     * Returns a token.
     */
    public function get(): Response
    {
        return $this->connector->send(new GetRequest());
    }
}
