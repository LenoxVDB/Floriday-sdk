<?php

namespace Lennord\FloridaySdk\Resources\Batch;


use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasFormBody;
use Saloon\Traits\Body\HasJsonBody;

class CreateRequest extends Request implements HasBody
{
    use HasJsonBody;

    public function __construct(
        private array $payload
    )
    {
    }

    /**
     * @inheritDoc
     */
    protected Method $method = Method::POST;

    /**
     * @inheritDoc
     */
    public function resolveEndpoint(): string
    {
        return '/batches';
    }

    /**
     * @inheritDoc
     */
    protected function defaultBody(): array
    {
        return $this->payload;
    }
}
