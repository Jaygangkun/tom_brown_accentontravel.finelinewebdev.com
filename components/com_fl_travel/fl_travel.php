<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

JLoader::register('sigConnect', JPATH_BASE . '/components/com_fl_travel/assets/sigConnect/sigConnect.php');
JLoader::register('sigConfig', JPATH_BASE . '/components/com_fl_travel/assets/sigConnect/sigConfig.php'); 
// JLoader::register('TemplateBuilder', JPATH_ADMINISTRATOR . '/components/com_fl_items/helpers/templateBuilder.php'); 
// JLoader::register('TemplateBuilderImages', JPATH_ADMINISTRATOR . '/components/com_fl_items/helpers/templateBuilderImages.php'); 

// Require the base controller
require_once (JPATH_COMPONENT.'/controller.php');

// Create the controller
$classname	= 'FLTravelController'.$controller;
$controller = new $classname();

// Perform the Request task
$controller->execute( JRequest::getVar('task'));

// Redirect if set by the controller
$controller->redirect();

?>
