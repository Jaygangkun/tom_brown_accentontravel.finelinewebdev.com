<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

JLoader::register('FliItem', JPATH_ADMINISTRATOR . '/components/com_fl_items/helpers/item/fliItem.php');
JLoader::register('FliField', JPATH_ADMINISTRATOR . '/components/com_fl_items/helpers/fields/fliField.php'); 
JLoader::register('TemplateBuilder', JPATH_ADMINISTRATOR . '/components/com_fl_items/helpers/templateBuilder.php'); 
JLoader::register('TemplateBuilderImages', JPATH_ADMINISTRATOR . '/components/com_fl_items/helpers/templateBuilderImages.php'); 

// Require the base controller
require_once (JPATH_COMPONENT.'/controller.php');

// Create the controller
$classname	= 'FLItemsController'.$controller;
$controller = new $classname();

// Perform the Request task
$controller->execute( JRequest::getVar('task'));

// Redirect if set by the controller
$controller->redirect();

?>
