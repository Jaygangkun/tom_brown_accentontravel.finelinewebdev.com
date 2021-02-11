<?php

/**
 * JCH Optimize - Performs several front-end optimizations for fast downloads
 *
 * @package   jchoptimize/joomla-platform
 * @author    Samuel Marshall <samuel@jch-optimize.net>
 * @copyright Copyright (c) 2020 Samuel Marshall / JCH Optimize
 * @license   GNU/GPLv3, or later. See LICENSE file
 *
 * If LICENSE file missing, see <http://www.gnu.org/licenses/>.
 */

defined('_JEXEC') or die;

use JchOptimize\Core\Helper;
use JchOptimize\Platform\Plugin;
use JchOptimize\Platform\Utility;
use Joomla\CMS\HTML\HTMLHelper;

include_once dirname(dirname(__FILE__)) . '/autoload.php';

class JFormFieldAjax extends JFormField
{
	protected $type = 'ajax';


	public function setup(SimpleXMLElement $element, $value, $group = NULL)
	{

		if (!defined('JCH_VERSION'))
		{
			define('JCH_VERSION', '6.3.1');
		}

		$params = Plugin::getPluginParams();

                if (!defined('JCH_DEBUG'))
                {
                        define('JCH_DEBUG', ($params->get('debug', 0) && JDEBUG));
                }

		static $cnt = 1;

		if($cnt == 1)
		{
			$script_options = array('framework' => false, 'relative' => true);

			if (version_compare(JVERSION, '4.0', 'lt'))
			{
				JHtml::script('jui/jquery.min.js', $script_options);
			}
			else
			{
				HTMLHelper::_('script', 'vendor/jquery/jquery.min.js', $script_options);
			}

			$oDocument = JFactory::getDocument();
			$sScript   = '';

			$options = array('version' => JCH_VERSION);
			$oDocument->addStyleSheet(JUri::root(true) . '/media/plg_jchoptimize/css/admin.css', $options);
			//$oDocument->addStyleSheet(JUri::root(true) . '/media/plg_jchoptimize/css/admin-joomla.css', $options);
			$oDocument->addScript(JUri::root(true) . '/media/plg_jchoptimize/js/admin-joomla.js', $options);
			$oDocument->addScript(JUri::root(true) . '/media/plg_jchoptimize/js/admin-utility.js', $options);

			$uri         = clone JUri::getInstance();
			$domain      = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port')) . Helper::getBaseFolder();
			$plugin_path = 'plugins/system/jch_optimize/';

			$ajax_url = $domain . 'administrator/index.php?option=com_jch_optimize';

			$sScript .= <<<JCHSCRIPT
function submitJchSettings(){
	Joomla.submitbutton('plugin.apply');
}                        
jQuery(document).ready(function() {
    jQuery('.collapsible').collapsible();
  });
			
var jch_observers = [];        
var jch_ajax_url = '$ajax_url';
JCHSCRIPT;

			$oDocument->addScriptDeclaration($sScript);
			$oDocument->addStyleSheet('//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.css');
			JHtml::script('plg_jchoptimize/jquery.collapsible.js', $script_options);

	                
		}

		$cnt++;
	 
		return false;
	}

        protected function getInput()
	{
		return false;
	}
}
