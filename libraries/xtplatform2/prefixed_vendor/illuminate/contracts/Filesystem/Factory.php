<?php /* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:20:25 */

namespace XTP_BUILD\Illuminate\Contracts\Filesystem;

interface Factory
{
    /**
     * Get a filesystem implementation.
     *
     * @param  string  $name
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    public function disk($name = null);
}
