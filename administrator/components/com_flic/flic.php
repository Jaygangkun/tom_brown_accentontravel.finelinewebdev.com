<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if (!JFactory::getUser()->authorise('core.manage', 'com_flic'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Set the table directory
JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_flic/tables');

$controllerName = JRequest::getCmd( 'view', 'galleries' );
$taskName = JRequest::getCmd( 'task', 'display' );

switch ($controllerName)
{
	// case 'categories' :
		// JSubMenuHelper::addEntry(JText::_('Galleries'), 'index.php?option=com_flic');
		// JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_flic&view=categories', true); 
		// break;
	default:
		JSubMenuHelper::addEntry(JText::_('Banners'), 'index.php?option=com_flic', true);
		// JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_flic&view=categories'); 
}


require_once( JPATH_COMPONENT.'/controllers/'.$controllerName.'.php' );
$controllerName = 'FLICController'.ucwords($controllerName);


// Create the controller
$controller = new $controllerName();

// Perform the Request task
$controller->execute( $taskName );

// Redirect if set by the controller
$controller->redirect();
