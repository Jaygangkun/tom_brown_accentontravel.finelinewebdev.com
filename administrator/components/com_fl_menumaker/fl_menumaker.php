<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if (!JFactory::getUser()->authorise('core.manage', 'com_flic'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Set the table directory
JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_flic/tables');

$controllerName = JRequest::getCmd( 'view', 'menu' );
$taskName = JRequest::getCmd( 'task', 'display' );

switch ($controllerName)
{
	default:
}


require_once( JPATH_COMPONENT.'/controllers/'.$controllerName.'.php' );
$controllerName = 'FL_MenuMakerController'.ucwords($controllerName);


// Create the controller
$controller = new $controllerName();

// Perform the Request task
$controller->execute( $taskName );

// Redirect if set by the controller
$controller->redirect();
