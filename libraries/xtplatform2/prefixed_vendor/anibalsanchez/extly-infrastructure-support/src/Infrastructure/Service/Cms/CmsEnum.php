<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Platform" on 2019-09-01 09:26:06 */

/*
 * @package     Extly Infrastructure Support
 *
 * @author      Extly, CB. <team@extly.com>
 * @copyright   Copyright (c)2007-2019 Extly, CB. All rights reserved.
 * @license     http://www.opensource.org/licenses/mit-license.html  MIT License
 *
 * @see         https://www.extly.com
 */

namespace XTP_BUILD\Extly\Infrastructure\Service\Cms;

use XTP_BUILD\MyCLabs\Enum\Enum;

class CmsEnum extends Enum
{
    const JOOMLA = 'Joomla';
    const PRESTASHOP = 'PrestaShop';
    const WORDPRESS = 'Wordpress';

    const MOCK = 'Mock';
}
