<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:35 */

namespace XTP_BUILD\Http\Discovery\Exception;

use XTP_BUILD\Http\Discovery\Exception;

/**
 * Thrown when a discovery does not find any matches.
 *
 * @final do NOT extend this class, not final for BC reasons
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
/*final */class NotFoundException extends \RuntimeException implements Exception
{
}
