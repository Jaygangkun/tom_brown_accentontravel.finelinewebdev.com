<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Platform" on 2019-09-01 09:25:38 */

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace XTP_BUILD\Carbon\Exceptions;

use Exception;
use InvalidArgumentException;

class BadUnitException extends InvalidArgumentException
{
    /**
     * Constructor.
     *
     * @param string          $unit
     * @param int             $code
     * @param \Exception|null $previous
     */
    public function __construct($unit, $code = 0, Exception $previous = null)
    {
        parent::__construct("Bad comparison unit: '$unit'", $code, $previous);
    }
}
