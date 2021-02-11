<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2020 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

// App
$app = JFactory::getApplication();

// ACL Check
if (!JFactory::getUser()->authorise('core.manage', 'com_rsfirewall'))
{
	$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
	$app->redirect(JRoute::_('index.php', false));
	return false;
}

require_once JPATH_COMPONENT . '/helpers/adapter.php';
require_once JPATH_COMPONENT . '/helpers/toolbar.php';
require_once JPATH_COMPONENT . '/helpers/version.php';
require_once JPATH_COMPONENT . '/helpers/config.php';
require_once JPATH_COMPONENT . '/controller.php';
	
$controller	= JControllerLegacy::getInstance('RSFirewall');

$task = $app->input->get('task');

$controller->execute($task);
$controller->redirect();