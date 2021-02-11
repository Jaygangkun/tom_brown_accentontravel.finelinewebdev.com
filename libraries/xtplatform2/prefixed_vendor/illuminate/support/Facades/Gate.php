<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Tailwind CSS" on 2019-08-30 11:38:34 */

namespace XTP_BUILD\Illuminate\Support\Facades;

use XTP_BUILD\Illuminate\Contracts\Auth\Access\Gate as GateContract;

/**
 * @see \Illuminate\Contracts\Auth\Access\Gate
 */
class Gate extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return GateContract::class;
    }
}