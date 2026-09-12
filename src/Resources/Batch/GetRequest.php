<?php

namespace Lennord\FloridaySdk\Resources\Batch;


use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasFormBody;
use Saloon\Traits\Body\HasJsonBody;

class GetRequest extends Request
{
    public function __construct(
        private string $batchId,
    )
    {
    }

    /**
     * @inheritDoc
     */
    protected Method $method = Method::GET;

    /**
     * @inheritDoc
     */
    public function resolveEndpoint(): string
    {
        return '/batches/' . $this->batchId;
    }
}
