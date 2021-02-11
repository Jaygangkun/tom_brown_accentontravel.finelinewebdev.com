<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:28 */

namespace XTP_BUILD\Http\Client\Common\Exception;

use XTP_BUILD\Http\Client\Exception\RequestException;

/**
 * Thrown when the Plugin Client detects an endless loop.
 *
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 */
class LoopException extends RequestException
{
}
