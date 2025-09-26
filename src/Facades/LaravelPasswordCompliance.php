<?php

namespace Inventas\LaravelPasswordCompliance\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Inventas\LaravelPasswordCompliance\LaravelPasswordCompliance
 */
class LaravelPasswordCompliance extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Inventas\LaravelPasswordCompliance\LaravelPasswordCompliance::class;
    }
}
