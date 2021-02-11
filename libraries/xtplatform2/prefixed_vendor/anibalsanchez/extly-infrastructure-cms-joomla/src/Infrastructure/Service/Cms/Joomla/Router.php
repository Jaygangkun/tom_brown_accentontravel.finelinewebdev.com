<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Platform" on 2019-09-01 09:26:16 */

/*
 * @package     Extly Infrastructure Support for Joomla
 *
 * @author      Extly, CB. <team@extly.com>
 * @copyright   Copyright (c)2007-2019 Extly, CB. All rights reserved.
 * @license     http://www.opensource.org/licenses/mit-license.html  MIT License
 *
 * @see         https://www.extly.com
 */

namespace XTP_BUILD\Extly\Infrastructure\Service\Cms\Joomla;

use XTP_BUILD\Extly\Infrastructure\Service\Cms\Contracts\RouterInterface;
use Joomla\CMS\Router\Route;

class Router implements RouterInterface
{
    public function route($url, $params = null)
    {
        return Route::_($url);
    }
}
