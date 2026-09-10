<?php

declare(strict_types=1);

namespace Lennord\FloridaySdk\Tests;

use Lennord\FloridaySdk\Facades\Floriday as FloridayFacade;
use Lennord\FloridaySdk\Resources\IdentitiesResource;
use Lennord\FloridaySdk\Resources\TokenResource;
use PHPUnit\Framework\Attributes\Test;

final class FacadeTest extends TestCase
{
    #[Test]
    public function facade_resolves_connector(): void
    {
        $this->assertInstanceOf(TokenResource::class, FloridayFacade::token());
        $this->assertInstanceOf(IdentitiesResource::class, FloridayFacade::identity());
    }
}
