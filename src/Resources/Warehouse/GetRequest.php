<?php

namespace Lennord\FloridaySdk\Resources\Warehouse;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetRequest extends Request
{
    /**
     * The constructor.
     */
    public function __construct(
        private bool $excludeExternal
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
        return '/warehouses';
    }

    /**
     * @inheritDoc
     */
    protected function defaultQuery(): array
    {
        $flag = $this->excludeExternal ? 'true' : 'false';

        return [
            'excludeExternalWarehouses' => $flag
        ];
    }
}
