<?php

/**
 * JCH Optimize - Performs several front-end optimizations for fast downloads
 *
 * @package   jchoptimize/core
 * @author    Samuel Marshall <samuel@jch-optimize.net>
 * @copyright Copyright (c) 2020 Samuel Marshall / JCH Optimize
 * @license   GNU/GPLv3, or later. See LICENSE file
 *
 * If LICENSE file missing, see <http://www.gnu.org/licenses/>.
 */

namespace JchOptimize\Core;

defined('_JEXEC') or die('Restricted access');

use JchOptimize\Platform\Settings;
use JchOptimize\Platform\Cache;
use JchOptimize\Platform\Paths;
use JchOptimize\Platform\Utility;
use JchOptimize\Core\ImageOptimizer;


class Ajax
{

        /**
         *
         * @param   Settings  $params
         *
         */
	public static function garbageCron(Settings $params)
	{
		Cache::gc();
	}

	
}
