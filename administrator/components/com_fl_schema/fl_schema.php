<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if (!JFactory::getUser()->authorise('core.manage', 'com_fl_schema'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Set the table directory
JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_fl_schema/tables');

$controllerName = JRequest::getCmd( 'view', 'schema' );
$taskName = JRequest::getCmd( 'task', 'edit' );

// switch ($controllerName)
// {
	// default:
		JSubMenuHelper::addEntry(JText::_('Schema'), 'index.php?option=com_fl_schema', true);
// }


require_once( JPATH_COMPONENT.'/controllers/'.$controllerName.'.php' );
$controllerName = 'Controller'.ucwords($controllerName);


// Create the controller
$controller = new $controllerName();

// Perform the Request task
$controller->execute( $taskName );

// Redirect if set by the controller
$controller->redirect();
