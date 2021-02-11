<?php /* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:19 */

namespace XTP_BUILD\Psr\SimpleCache;

/**
 * Exception interface for invalid cache arguments.
 *
 * When an invalid argument is passed it must throw an exception which implements
 * this interface
 */
interface InvalidArgumentException extends CacheException
{
}
