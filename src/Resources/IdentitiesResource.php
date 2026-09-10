<?php

namespace Lennord\FloridaySdk\Resources;

use Lennord\FloridaySdk\Resources\Base\BaseResource;
use Lennord\FloridaySdk\Resources\Identities\IdentityRequest;
use Saloon\Http\Response;

class IdentitiesResource extends BaseResource
{
    /**
     * Gets an identity.
     */
    public function get(): Response
    {
        return $this->connector->withAuthorization()->send(new IdentityRequest());
    }
}
