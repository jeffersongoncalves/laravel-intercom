<?php

namespace JeffersonGoncalves\Intercom\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JeffersonGoncalves\Intercom\Intercom
 */
class Intercom extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \JeffersonGoncalves\Intercom\Intercom::class;
    }
}
