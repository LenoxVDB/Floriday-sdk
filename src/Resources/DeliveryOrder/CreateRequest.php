<?php

namespace Lennord\FloridaySdk\Resources\DeliveryOrder;


use Illuminate\Support\Str;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
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
        return '/delivery-orders/' . Str::uuid7() . '/goods-movement';
    }

    /**
     * @inheritDoc
     */
    protected function defaultBody(): array
    {
        return $this->payload;
    }
}
