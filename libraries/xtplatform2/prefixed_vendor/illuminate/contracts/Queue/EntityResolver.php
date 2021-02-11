<?php /* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:20:24 */

namespace XTP_BUILD\Illuminate\Contracts\Queue;

interface EntityResolver
{
    /**
     * Resolve the entity for the given ID.
     *
     * @param  string  $type
     * @param  mixed  $id
     * @return mixed
     */
    public function resolve($type, $id);
}
