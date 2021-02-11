<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:35 */

namespace XTP_BUILD\Http\Discovery\Exception;

use XTP_BUILD\Http\Discovery\Exception;

/**
 * This exception is thrown when we cannot use a discovery strategy. This is *not* thrown when
 * the discovery fails to find a class.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class StrategyUnavailableException extends \RuntimeException implements Exception
{
}
