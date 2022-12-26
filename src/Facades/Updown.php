<?php

namespace Crastos\Updown\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Crastos\Updown\Updown
 */
class Updown extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Crastos\Updown\Updown::class;
    }
}
