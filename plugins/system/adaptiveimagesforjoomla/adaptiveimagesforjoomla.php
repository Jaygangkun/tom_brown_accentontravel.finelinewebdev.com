<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Search for Algolia" on 2019-08-30 11:27:29 */

/*
 * @package    XT Adaptive Images PRO
 *
 * @author     Extly, CB <team@extly.com>
 * @copyright  Copyright (c)2007-2019 Extly, CB All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 * @link       https://www.extly.com
 */

use XTP_BUILD\Extly\Infrastructure\Service\Cms\Joomla\PluginHelper;
use Joomla\CMS\Factory as CMSFactory;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Uri\Uri as CMSUri;

defined('_JEXEC') or die;

/**
 * PlgSystemAdaptiveImagesForJoomla class.
 *
 * @since       1.0
 */
class PlgSystemAdaptiveImagesForJoomla extends CMSPlugin
{
    private $passed = false;

    private $adaptiveImagesHelper;

    /**
     * onBeforeCompileHead.
     */
    public function onBeforeCompileHead()
    {
        if ((CMSFactory::getApplication()->isAdmin()) || (!$this->getHelper())) {
            return;
        }

        $this->adaptiveImagesHelper->addScripts();
    }

    /**
     * onAfterRender.
     */
    public function onAfterRender()
    {
        if ((CMSFactory::getApplication()->isAdmin()) || (!$this->getHelper())) {
            return;
        }

        $this->adaptiveImagesHelper->apply();

        $this->passed = true;
    }

    /**
     * Create the helper object.
     *
     * @return object The plugins helper object
     */
    private function getHelper()
    {
        if ($this->passed) {
            return null;
        }

        require_once JPATH_ROOT.'/libraries/xtplatform2/vendor/autoload.php';

        $url = CMSUri::getInstance()->toString();

        if (!PluginHelper::isPluginEnabledUrl($this->params, $url)) {
            $this->passed = true;

            return null;
        }

        // Already initialized, so return
        if ($this->adaptiveImagesHelper) {
            return $this->adaptiveImagesHelper;
        }

        require_once 'helper.php';

        $this->adaptiveImagesHelper = new AdaptiveImagesForJoomlaHelper($this->params);

        return $this->adaptiveImagesHelper;
    }
}
