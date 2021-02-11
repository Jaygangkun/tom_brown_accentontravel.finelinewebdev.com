<?php /* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:20:23 */

namespace XTP_BUILD\Illuminate\Contracts\Queue;

interface Factory
{
    /**
     * Resolve a queue connection instance.
     *
     * @param  string  $name
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connection($name = null);
}
