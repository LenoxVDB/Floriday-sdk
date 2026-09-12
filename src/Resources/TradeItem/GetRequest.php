<?php

namespace Lennord\FloridaySdk\Resources\TradeItem;

use Saloon\Enums\Method;
use Saloon\Http\Request;

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
        return '/trade-items';
    }
}
