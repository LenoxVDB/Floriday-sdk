<?php

namespace Lennord\FloridaySdk\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Lennord\FloridaySdk\FloridaySdk
 */
class FloridaySdk extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Lennord\FloridaySdk\FloridaySdk::class;
    }
}
