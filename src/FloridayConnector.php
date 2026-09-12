<?php

namespace Lennord\FloridaySdk;

use Lennord\FloridaySdk\Concerns\HasAuthToken;
use Lennord\FloridaySdk\Resources\BatchResource;
use Lennord\FloridaySdk\Resources\DeliveryOrderResource;
use Lennord\FloridaySdk\Resources\FulfillmentOrderResource;
use Lennord\FloridaySdk\Resources\IdentitiesResource;
use Lennord\FloridaySdk\Resources\TokenResource;
use Lennord\FloridaySdk\Resources\TradeItemResource;
use Lennord\FloridaySdk\Resources\WarehouseResource;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

class FloridayConnector extends Connector
{
    use HasAuthToken;
    use AlwaysThrowOnErrors;

    /**
     * @inheritDoc
     */
    public function resolveBaseUrl(): string
    {
        return rtrim(config('floriday-sdk.base_url'), '/');
    }

    /**
     * @inheritDoc
     */
    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Returns the batch resource.
     */
    public function batch(): BatchResource
    {
        return new BatchResource($this);
    }

    /**
     * Returns the identity resource.
     */
    public function identity(): IdentitiesResource
    {
        return new IdentitiesResource($this);
    }

    /**
     * Returns the trade resource.
     */
    public function trade(): TradeItemResource
    {
        return new TradeItemResource($this);
    }

    /**
     * Returns the warehouse resource.
     */
    public function warehouse(): WarehouseResource
    {
        return new WarehouseResource($this);
    }

    /**
     * Returns the fulfillment order resource.
     */
    public function fulfillment(): FulfillmentOrderResource
    {
        return new FulfillmentOrderResource($this);
    }

    /**
     * Returns the delivery order resource.
     */
    public function delivery(): DeliveryOrderResource
    {
        return new DeliveryOrderResource($this);
    }

    /**
     * Returns the token resource.
     */
    public function token(): TokenResource
    {
        return new TokenResource($this);
    }
}
