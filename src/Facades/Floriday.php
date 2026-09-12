<?php

namespace Lennord\FloridaySdk\Facades;

use Illuminate\Support\Facades\Facade;
use Lennord\FloridaySdk\FloridayConnector;
use Lennord\FloridaySdk\Resources\BatchResource;
use Lennord\FloridaySdk\Resources\DeliveryOrderResource;
use Lennord\FloridaySdk\Resources\FulfillmentOrderResource;
use Lennord\FloridaySdk\Resources\IdentitiesResource;
use Lennord\FloridaySdk\Resources\TokenResource;
use Lennord\FloridaySdk\Resources\TradeItemResource;
use Lennord\FloridaySdk\Resources\WarehouseResource;

/**
 * @method static TokenResource token()
 * @method static BatchResource batch()
 * @method static WarehouseResource warehouse()
 * @method static TradeItemResource trade()
 * @method static IdentitiesResource identity()
 * @method static FulfillmentOrderResource fulfillment()
 * @method static DeliveryOrderResource delivery()
 *
 * @see FloridayConnector
 */
class Floriday extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return FloridayConnector::class;
    }
}
