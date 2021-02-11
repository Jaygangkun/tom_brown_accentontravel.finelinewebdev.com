<?php
/**
 * @version		$Id: banner.php 10878 2008-08-30 17:29:13Z willebil $
 * @package		Joomla
 * @subpackage	Banners
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.controller' );

/**
 * @package		Joomla
 * @subpackage	Banners
 */
class ControllerSchema extends JControllerLegacy
{
	/**
	 * Constructor
	 */
	function __construct( $config = array() )
	{
		parent::__construct( $config );
		// Register Extra tasks
		$this->registerTask( 'add',			'edit' );
		$this->registerTask( 'apply',		'save' );
		$this->registerTask( 'unpublish',	'publish' );
	}

	function display()
	{
	}

	function edit()
	{
		$db	=& JFactory::getDBO();
	
		$lists = array();

		// Load Schema
		$query = 'SELECT * FROM #__fl_schema WHERE fl_schema_id = 1';
		$db->setQuery( $query );
		$row = $db->loadObject();
		
		require_once(JPATH_COMPONENT.'/views/schema.php');
		ViewSchema::schema( $row, $lists );
	}

	/**
	 * Save method
	 */
	function save()
	{
		global $mainframe;
		
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_fl_schema' );
		// Initialize variables
		$db =& JFactory::getDBO();

		$post = JRequest::get( 'post' );
		
		$row =& JTable::getInstance('schema', 'FLSchemaTable');
		$row->load(1);
		
		if (!$row->bind( $post )) {
			return JError::raiseWarning( 500, $row->getError() );
		}
		
		$imageFileTypes = array('image/png', 'image/jpeg');
		
		$newLogoUpload = JRequest::getVar('new-logo', null, 'files', 'array');
		if (isset($newLogoUpload) && in_array($newLogoUpload['type'], $imageFileTypes)) {
			JFile::delete(JPATH_ROOT . '/' . $row->logo);
			$thisFilename = JFilterOutput::stringURLSafe(JFile::stripExt($newLogoUpload['name'])). '.' . JFile::getExt($newLogoUpload['name']);
			JFile::upload($newLogoUpload['tmp_name'], JPATH_ROOT . '/' . $thisFilename);
			$row->logo = $thisFilename;
		}
		
		$newImageUpload = JRequest::getVar('new-image', null, 'files', 'array');
		if (isset($newImageUpload) && in_array($newImageUpload['type'], $imageFileTypes)) {
			JFile::delete(JPATH_ROOT . '/' . $row->logo);
			$thisFilename = JFilterOutput::stringURLSafe(JFile::stripExt($newImageUpload['name'])). '.' . JFile::getExt($newImageUpload['name']);
			JFile::upload($newImageUpload['tmp_name'], JPATH_ROOT . '/' . $thisFilename);
			$row->image = $thisFilename;
		}
		
		if (!$row->store()) {
			return JError::raiseWarning( $row->getError() );
		}
		
		
		$task = JRequest::getCmd( 'task' );
		switch ($task)
		{
			case 'save':
				$link = 'index.php';
				break;
			case 'apply':
			default:
				$link = 'index.php?option=com_fl_schema&task=edit';
				break;
		}
		$this->setRedirect( $link, JText::_( 'Item Saved' ) );
	}

	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$this->setRedirect( 'index.php' );
	}

	function publish()
	{
	}
}
