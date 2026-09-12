<?php

namespace Lennord\FloridaySdk\Resources\Identities;


use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasFormBody;
use Saloon\Traits\Body\HasJsonBody;

class GetRequest extends Request
{
    /**
     * @inheritDoc
     */
    protected Method $method = Method::GET;

    /**
     * @inheritDoc
     */
    public function resolveEndpoint(): string
    {
        return '/identities';
    }
}
