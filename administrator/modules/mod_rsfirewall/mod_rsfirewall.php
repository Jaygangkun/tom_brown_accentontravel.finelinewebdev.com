<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2020 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// logged in user
$user = JFactory::getUser();

if (!file_exists(JPATH_ADMINISTRATOR.'/components/com_rsfirewall/helpers/config.php'))
{
	return false;
}

require_once JPATH_ADMINISTRATOR.'/components/com_rsfirewall/helpers/config.php';

JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_rsfirewall/models');
$model = JModelLegacy::getInstance('RSFirewall', 'RsfirewallModel', array(
    'option' => 'com_rsfirewall',
    'table_path' => JPATH_ADMINISTRATOR.'/components/com_rsfirewall/tables'
));

if ($model && $user->authorise('core.admin', 'com_rsfirewall')) {
    $config = RSFirewallConfig::getInstance();

	// load the frontend language
	// this language file contains some event log translations
	$lang = JFactory::getLanguage();
		
	$lang->load('com_rsfirewall', JPATH_SITE, 'en-GB', true);
	$lang->load('com_rsfirewall', JPATH_SITE, $lang->getDefault(), true);
	$lang->load('com_rsfirewall', JPATH_SITE, null, true);

    JHtml::_('stylesheet', 'com_rsfirewall/style.css', array('relative' => true, 'version' => 'auto'));
    JHtml::_('stylesheet', 'mod_rsfirewall/style.css', array('relative' => true, 'version' => 'auto'));

	// Load jQuery
    JHtml::_('jquery.framework');

    JHtml::_('script', 'com_rsfirewall/rsfirewall.js', array('relative' => true, 'version' => 'auto'));
    JHtml::_('script', 'mod_rsfirewall/rsfirewall.js', array('relative' => true, 'version' => 'auto'));
	
	$logs = array();
	if ($user->authorise('logs.view', 'com_rsfirewall')) {
		$logs 	= $model->getLastLogs();
		$logNum = $model->getLogOverviewNum();
	}
	
	$grade = $config->get('grade');
	if (!$grade) {
		$color = '#000';
	}
	elseif ($grade <= 75) {
		$color = '#ED7A53';
	} elseif ($grade <= 90) {
		$color = '#88BBC8';
	} elseif ($grade <= 100) {
		$color = '#9FC569';
	}
	
	// Load GeoIP helper class
	require_once JPATH_ADMINISTRATOR.'/components/com_rsfirewall/helpers/geoip/geoip.php';
	$geoip = RSFirewallGeoIP::getInstance();
	
	require JModuleHelper::getLayoutPath('mod_rsfirewall');
}