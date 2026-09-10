<?php

declare(strict_types=1);

namespace Lennord\FloridaySdk\Tests;

use Lennord\FloridaySdk\FloridayConnector;
use Lennord\FloridaySdk\Resources\BatchResource;
use Lennord\FloridaySdk\Resources\IdentitiesResource;
use Lennord\FloridaySdk\Resources\TokenResource;
use Lennord\FloridaySdk\Resources\TradeItemResource;
use Lennord\FloridaySdk\Resources\WarehouseResource;
use PHPUnit\Framework\Attributes\Test;

final class ConnectorTest extends TestCase
{
    #[Test]
    public function connector_exposes_resources_and_defaults(): void
    {
        $sdk = app(FloridayConnector::class);

        $this->assertSame('https://api.test', $sdk->resolveBaseUrl());
        $this->assertInstanceOf(TokenResource::class, $sdk->token());
        $this->assertInstanceOf(IdentitiesResource::class, $sdk->identity());
        $this->assertInstanceOf(TradeItemResource::class, $sdk->trade());
        $this->assertInstanceOf(WarehouseResource::class, $sdk->warehouse());
        $this->assertInstanceOf(BatchResource::class, $sdk->batch());
    }
}
