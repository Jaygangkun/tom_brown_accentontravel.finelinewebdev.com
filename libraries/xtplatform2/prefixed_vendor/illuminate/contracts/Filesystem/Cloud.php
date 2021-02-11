<?php /* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:20:25 */

namespace XTP_BUILD\Illuminate\Contracts\Filesystem;

interface Cloud extends Filesystem
{
    /**
     * Get the URL for the file at the given path.
     *
     * @param  string  $path
     * @return string
     */
    public function url($path);
}
