<?php /* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:20:26 */

namespace XTP_BUILD\Illuminate\Contracts\Routing;

interface BindingRegistrar
{
    /**
     * Add a new route parameter binder.
     *
     * @param  string  $key
     * @param  string|callable  $binder
     * @return void
     */
    public function bind($key, $binder);

    /**
     * Get the binding callback for a given binding.
     *
     * @param  string  $key
     * @return \Closure
     */
    public function getBindingCallback($key);
}
