<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:23:39 */

// Register chunk filter if not found
if (!array_key_exists('chunk', stream_get_filters())) {
    stream_filter_register('chunk', 'XTP_BUILD\Http\Message\Encoding\Filter\Chunk');
}
