<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Platform" on 2019-09-01 09:26:03 */

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

use XTP_BUILD\Cron\CronExpression as DragonCronExpression;
use XTP_BUILD\Extly\Infrastructure\Creator\CreatorTrait;

class CronExpression
{
    use CreatorTrait;

    const EVERY_MINUTE = '* * * * *';

    public function __construct($expression)
    {
        $this->cronExpression = DragonCronExpression::factory($expression);
    }

    /**
     * nextDate.
     *
     * @param string $dateTime Param
     * @param string $tz       Param
     *
     * @return string
     */
    public function nextDate($dateTime = Date::NOW)
    {
        if (Date::NOW === $dateTime) {
            $dateTime = Date::now();
        } else {
            $dateTime = Date::parse($dateTime);
        }

        $phpDateTime = $dateTime->toPhpDateTime();
        $nextDate = $this->cronExpression->getNextRunDate($phpDateTime);
        $result = Date::parse(Date::formatDateTime($nextDate));

        return $result;
    }
}
