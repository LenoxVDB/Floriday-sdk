<?php

namespace Lennord\FloridaySdk\Resources\Batch;


use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasFormBody;
use Saloon\Traits\Body\HasJsonBody;

class PriceRequest extends Request implements HasBody
{
    use HasJsonBody;

    public function __construct(
        private array  $payload,
        private string $batchId,
    )
    {
    }

    /**
     * @inheritDoc
     */
    protected Method $method = Method::PUT;

    /**
     * @inheritDoc
     */
    public function resolveEndpoint(): string
    {
        return sprintf('/batches/%s/base-supply', $this->batchId);
    }

    /**
     * @inheritDoc
     */
    protected function defaultBody(): array
    {
        return $this->payload;
    }
}
