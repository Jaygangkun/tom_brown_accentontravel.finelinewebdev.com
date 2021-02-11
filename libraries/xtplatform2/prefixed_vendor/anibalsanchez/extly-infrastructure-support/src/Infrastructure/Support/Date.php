<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Platform" on 2019-09-01 09:26:00 */

/*
 * @package     Extly Infrastructure Support
 *
 * @author      Extly, CB. <team@extly.com>
 * @copyright   Copyright (c)2007-2019 Extly, CB. All rights reserved.
 * @license     http://www.opensource.org/licenses/mit-license.html  MIT License
 *
 * @see         https://www.extly.com
 */

namespace XTP_BUILD\Extly\Infrastructure\Support;

use XTP_BUILD\Carbon\Carbon;
use DateTime;
use DateTimeZone;

class Date extends Carbon
{
    const NOW = 'now';

    const UTC = 'utc';

    const DATETIME_FORMAT = 'Y-m-d H:i:s';

    const DATE_ZERO = '0000-00-00 00:00:00';

    public function __construct($time = null, $tz = null)
    {
        if (!$tz) {
            $tz = self::UTC;
        }

        parent::__construct($time, $tz);
    }

    public function toPhpDateTime()
    {
        $dateTime = new DateTime();
        $dateTime->setTimestamp($this->timestamp);

        if (is_string($this->timezone)) {
            $dateTimeZone = new DateTimeZone($this->timezone);
        } else {
            $dateTimeZone = $this->timezone;
        }

        $dateTime->setTimezone($dateTimeZone);

        return $dateTime;
    }

    public static function formatDateTime(DateTime $dateTime)
    {
        return $dateTime->format(self::DATETIME_FORMAT);
    }

    public function toSql()
    {
        return $this->format(self::DATETIME_FORMAT);
    }
}
