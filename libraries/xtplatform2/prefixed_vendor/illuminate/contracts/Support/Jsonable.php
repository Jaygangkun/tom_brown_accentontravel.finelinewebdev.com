<?php /* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:20:23 */

namespace XTP_BUILD\Illuminate\Contracts\Support;

interface Jsonable
{
    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0);
}
