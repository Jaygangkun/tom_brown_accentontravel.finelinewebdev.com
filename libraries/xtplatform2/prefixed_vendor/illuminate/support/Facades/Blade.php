<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Tailwind CSS" on 2019-08-30 11:38:32 */

namespace XTP_BUILD\Illuminate\Support\Facades;

/**
 * @see \Illuminate\View\Compilers\BladeCompiler
 */
class Blade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return static::$app['view']->getEngineResolver()->resolve('blade')->getCompiler();
    }
}
