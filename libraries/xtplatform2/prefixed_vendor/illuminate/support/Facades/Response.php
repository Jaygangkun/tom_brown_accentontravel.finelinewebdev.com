<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Tailwind CSS" on 2019-08-30 11:38:30 */

namespace XTP_BUILD\Illuminate\Support\Facades;

use XTP_BUILD\Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

/**
 * @see \Illuminate\Contracts\Routing\ResponseFactory
 */
class Response extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ResponseFactoryContract::class;
    }
}
