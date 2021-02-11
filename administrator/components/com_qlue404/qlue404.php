<?php 

// no direct access
defined('_JEXEC') or die('Restircted Access');

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_qlue404')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Load our helper class
JLoader::register('Qlue404Helper', dirname(__FILE__).DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php');

// Get Qlue 404 controller
$controller = JControllerLegacy::getInstance('Qlue404');

// Execute any tasks
$controller->execute(JFactory::getApplication()->input->get('task'));

// Redirect is set by the controller
$controller->redirect();

?>