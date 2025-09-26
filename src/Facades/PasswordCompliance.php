<?php

namespace Inventas\PasswordCompliance\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Inventas\PasswordCompliance\PasswordCompliance
 */
class PasswordCompliance extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Inventas\PasswordCompliance\PasswordCompliance::class;
    }
}
