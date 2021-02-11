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
class FLICControllercategories extends JControllerLegacy
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
		$mainframe = JFactory::getApplication();
		$db =& JFactory::getDBO();

		$context			= 'com_flic.project.categories.list.';
		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order',		'p.treeLeft',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir',	'',			'word' );
		$filter_state		= $mainframe->getUserStateFromRequest( $context.'filter_state',		'filter_state',		'',			'word' );

		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart = ($mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 1, 'int' ) - 1) * $limit;

		$where = array();

		if ( $filter_state )
		{
			if ( $filter_state == 'P' ) {
				$where[] = 'p.showCategory = 1';
			}
			else if ($filter_state == 'U' ) {
				$where[] = 'p.showCategory = 0';
			}
		}

		$where		= count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '';
		$orderby	= ' ORDER BY '. $filter_order .' '. $filter_order_Dir .', p.flic_category_id';

		// get the total number of records
		$query = 'SELECT COUNT(*)'
		. ' FROM #__flic_category AS p'
		. $where
		;
		$db->setQuery( $query );
		$total = $db->loadResult();

		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $total, $limitstart, $limit );

		$query = 'SELECT p.* '
		. ' FROM #__flic_category AS p'
		. $where
		. $orderby
		;

		$db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
		$rows = $db->loadObjectList();
		
		// state filter
		$lists['state']	= JHTML::_('grid.state',  $filter_state );

		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;
		
		require_once(JPATH_COMPONENT.'/views/category.php');
		FLICViewCategory::categories( $rows, $pageNav, $lists );
	}

	function edit()
	{
		$db	=& JFactory::getDBO();
		$user	=& JFactory::getUser();
	
		$task = JRequest::getCmd( 'task' );
		if ($task == 'edit') {
			$category_id	= JRequest::getVar('flic_category_id', array(0), 'method', 'array'); 
			$category_id	= array((int) $category_id[0]);
			$isEdit = 1;
		} else {
			$category_id	= array( 0 );
			$isEdit = 0;
		}

		$option = JRequest::getCmd('option');

		$lists = array();
		
		$row =& JTable::getInstance('categories', 'FLICTable');
		$row->load( $category_id[0] );
		
		$query = 'SELECT p.* '
		. ' FROM #__flic_category AS p';
		if($isEdit) { $query .= ' WHERE p.flic_category_id != ' . $row->flic_category_id; }
		$query .= ' ORDER BY p.treeLeft'
		;
		
		$db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
		$parentList = $db->loadObjectList();
		$parentList[] = (object)array('flic_category_id' => 0, 'name' => "--Root--");
		$lists['parents'] = JHTML::_('select.genericlist',  $parentList, 'parent_flic_category_id', 'class="inputbox" size="1"','flic_category_id', 'name', $row->parent_flic_category_id );
		

		if ($category_id[0]) {
			$row->checkout( $user->get('id') );
		} else {
			$row->showCategory = 1;
		}
		
		// published
		$lists['showCategory'] = JHTML::_('select.booleanlist',  'showCategory', '', $row->showCategory );

		require_once(JPATH_COMPONENT.'/views/category.php');
		FLICViewCategory::category( $row, $lists );
	}

	/**
	 * Save method
	 */
	function save()
	{
		global $mainframe;
		
		require_once(JPATH_COMPONENT.'/helpers/tree.php');
		
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_flic&view=categories' );
		// Initialize variables
		$db =& JFactory::getDBO();
		jimport('joomla.filesystem.file');

		$post	= JRequest::get( 'post' );
		
		if(empty($post['alias'])) {
			$post['alias'] = $post['name'];
		}
		$post['alias'] = JFilterOutput::stringURLSafe($post['alias']);
		
		$post['description'] = JRequest::getVar( 'description', '', 'post', 'string', JREQUEST_ALLOWHTML ); 
		$post['shortDescription'] = JRequest::getVar( 'shortDescription', '', 'post', 'string', JREQUEST_ALLOWHTML ); 
		
		$row =& JTable::getInstance('categories', 'FLICTable');
		
		if (!$row->bind( $post )) {
			return JError::raiseWarning( 500, $row->getError() );
		}

		if (!$row->check()) {
			return JError::raiseWarning( 500, $row->getError() );
		}

		if($row->flic_category_id ) {
			$isUpdate = 1;
		} else {
			$isUpdate = 0;
		}
		
		$getParent = "SELECT p.treeRight, p.treeLevel, p.treeLeft FROM #__flic_category p WHERE p.flic_category_id = " . $row->parent_flic_category_id;
		$db->setQuery( $getParent );
		$parent = $db->loadObject();
		
		// If new category in ROOT
		if($row->parent_flic_category_id == 0) {
			$getParent = "SELECT MAX(p.treeRight) FROM #__flic_category p";
			$db->setQuery( $getParent );
			$newLeft = $db->loadResult();
			
			$row->treeLeft = $newLeft;
			$row->treeRight = $newLeft + 1;
			$row->treeLevel = 0;
			if (!$row->store()) {
				return JError::raiseWarning( $row->getError() );
			}
			$row->checkin();
		} else if($isUpdate == 0) {// If new category
			$getParentRight = "UPDATE #__flic_category SET treeRight=treeRight+2 WHERE treeRight >= " . $parent->treeRight;
			$db->setQuery( $getParentRight );
			$db->execute();
			
			$getParentRight = "UPDATE #__flic_category SET treeLeft=treeLeft+2 WHERE treeLeft > " . $parent->treeRight;
			$db->setQuery( $getParentRight );
			$db->execute();
			
			$row->treeLeft = $parent->treeRight;
			$row->treeRight = $parent->treeRight + 1;
			$row->treeLevel = $parent->treeLevel + 1;
			if (!$row->store()) {
				return JError::raiseWarning( $row->getError() );
			}
	
			$row->checkin();
		} else { // If it's an update. Set Parent and rebuild tree
			$updateParent = "UPDATE #__flic_category SET parent_flic_category_id = " . $row->parent_flic_category_id . " WHERE flic_category_id = " . $row->flic_category_id;
			$db->setQuery( $updateParent );
			$db->execute();
			if (!$row->store()) {
				return JError::raiseWarning( $row->getError() );
			}
			rebuild_tree();
		}
		// $row->imageFilename = $this_filename;
		
		$task = JRequest::getCmd( 'task' );
		
		switch ($task)
		{
			case 'apply':
				$link = 'index.php?option=com_flic&view=categories&task=edit&flic_category_id[]='. $row->flic_category_id ;
				break;

			case 'save':
			default:
				$link = 'index.php?option=com_flic&view=categories';
				break;
		}
		$this->setRedirect( $link, JText::_( 'Item Saved' ) );
	}

	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_flic&view=categories' );

		// Initialize variables
		$db	=& JFactory::getDBO();
		$post	= JRequest::get( 'post' );
		$row    =& JTable::getInstance('categories', 'FLICTable');
		$row->bind( $post );
		$row->checkin();
	}

	function publish()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_flic&view=categories' );

		// Initialize variables
		$db		=& JFactory::getDBO();
		$user		=& JFactory::getUser();
		$flic_category_id	= JRequest::getVar( 'flic_category_id', array(), 'post', 'array' );
		$task		= JRequest::getCmd( 'task' );
		$publish	= ($task == 'publish');
		$n			= count( $flic_category_id );

		if (empty( $flic_category_id )) {
			return JError::raiseWarning( 500, JText::_( 'No items selected' ) );
		}

		JArrayHelper::toInteger( $flic_category_id );
		$flic_category_ids = implode( ',', $flic_category_id);

		$query = 'UPDATE #__flic_category'
		. ' SET showCategory = ' . (int) $publish
		. ' WHERE flic_category_id IN ( '. $flic_category_ids .'  )'
		. ' AND ( checked_out = 0 OR ( checked_out = ' .(int) $user->get('id'). ' ) )'
		;
		$db->setQuery( $query );
		if (!$db->query()) {
			return JError::raiseWarning( 500, $db->getError() );
		}
		$this->setMessage( JText::sprintf( $publish ? 'Items published' : 'Items unpublished', $n ) );
	}


	function remove()
	{
		
		require_once(JPATH_COMPONENT.'/helpers/tree.php');
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_flic&view=categories' );

		// Initialize variables
		$db		=& JFactory::getDBO();
		$flic_category_id = JRequest::getVar( 'flic_category_id', array(), 'post', 'array' );
		$n		= count( $flic_category_id );
		JArrayHelper::toInteger( $flic_category_id );
		
		foreach($flic_category_id as $category) {
			$query = 'SELECT parent_flic_category_id FROM #__flic_category WHERE flic_category_id = ' . $category;
			$db->setQuery( $query );
			$deletedParent = $db->loadResult();
			
			
			$query = 'UPDATE #__flic_category SET parent_flic_category_id = ' . $deletedParent . ' WHERE parent_flic_category_id = ' . $category;
			$db->setQuery( $query );
			if (!$db->query()) {
				JError::raiseWarning( 500, $db->getError() );
			}
		}

		if ($n)
		{
			$query = 'DELETE FROM #__flic_category'
			. ' WHERE flic_category_id = ' . implode( ' OR flic_category_id = ', $flic_category_id )
			;
			$db->setQuery( $query );

			if (!$db->query()) {
				JError::raiseWarning( 500, $db->getError() );
			}
		}

		rebuild_tree();

		$this->setMessage( JText::sprintf( 'Items removed', $n ) );
	}

}
