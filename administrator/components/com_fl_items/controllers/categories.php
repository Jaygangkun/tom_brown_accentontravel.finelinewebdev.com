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
class FLItemsControllercategories extends JControllerAdmin
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

		$context			= 'com_fl_items.item.categories.list.';
		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order',		'p.ordering',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir',	'',			'word' );
		$filter_state		= $mainframe->getUserStateFromRequest( $context.'filter_state',		'filter_state',		'',			'word' );

		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart = $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0, 'int' );
		
		if ($filter_order == "p.ordering")
		{
		    $saveOrderingUrl = 'index.php?option=com_fl_items&view=categories&task=saveOrderAjax&tmpl=component';
		    JHtml::_('sortablelist.sortable', 'itemList', 'adminForm', strtolower($filter_order_Dir), $saveOrderingUrl);
		}

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
		$orderby	= ' ORDER BY '. $filter_order .' '. $filter_order_Dir .', p.item_category_id';

		// get the total number of records
		$query = 'SELECT COUNT(*)'
		. ' FROM #__fl_items_category AS p'
		. $where
		;
		$db->setQuery( $query );
		$total = $db->loadResult();

		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $total, $limitstart, $limit );

		$query = 'SELECT p.* '
		. ' FROM #__fl_items_category AS p'
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

		require_once(JPATH_COMPONENT.'/'.'views'.'/'.'categories.php');
		FLItemsViewCategory::categories( $rows, $pageNav, $lists );
	}

	function edit()
	{
		$db	=& JFactory::getDBO();
		$user	=& JFactory::getUser();
	
		$task = JRequest::getCmd( 'task' );
		if ($task == 'edit') {
			$category_id	= JRequest::getVar('item_category_id', array(0), 'method', 'array'); 
			$category_id	= array((int) $category_id[0]);
		} else {
			$category_id	= array( 0 );
		}

		$option = JRequest::getCmd('option');

		$lists = array();

		$row =& JTable::getInstance('categories', 'FLItemsTable');
		$row->load( $category_id[0] );

		if ($category_id[0]) {
			$row->checkout( $user->get('id') );
		} else {
			$row->showCategory = 1;
		}
		
		// Get all other items for sub-item dropdown
		if($row->item_category_id) {
			$query = "SELECT * FROM #__fl_items_category WHERE showCategory = 1 AND isSubItem = 0 AND item_category_id <> $row->item_category_id";
			$db->setQuery( $query );
			$getSubItemParents = $db->loadObjectList();
		} else {
			$getSubItemParents = array();
		}

		$rootItem = new stdClass();
		$rootItem->item_category_id = 0;
		$rootItem->name = "-ROOT-";
		array_unshift($getSubItemParents, $rootItem);
		
		// published
		$lists['showCategory'] = JHTML::_('select.booleanlist',  'showCategory', '', $row->showCategory );
		$lists['hasImages'] = JHTML::_('select.booleanlist',  'hasImages', '', $row->hasImages );
		$lists['isSingleImage'] = JHTML::_('select.booleanlist',  'isSingleImage', '', $row->isSingleImage );
        $lists['isSubItem'] = JHTML::_('select.booleanlist',  'isSubItem', '', $row->isSubItem );
        $lists['isNewFirst'] = JHTML::_('select.booleanlist',  'isNewFirst', '', $row->isNewFirst );
        $lists['isLinkToUser'] = JHTML::_('select.booleanlist',  'isLinkToUser', '', $row->isLinkToUser );
        $lists['isFeaturedEnabled'] = JHTML::_('select.booleanlist',  'isFeaturedEnabled', '', $row->isFeaturedEnabled );
        $lists['usersEditOnly'] = $row->usersEditOnly;
		$lists['subItemParentId'] = JHTML::_('select.genericlist', $getSubItemParents, 'subItemParentId', 'class="inputbox" size="1"','item_category_id', 'name', $row->subItemParentId );
		$lists['parent_category_id'] = JHTML::_('select.genericlist', $getSubItemParents, 'parent_category_id', 'class="inputbox" size="1"','item_category_id', 'name', $row->parent_category_id );

		require_once(JPATH_COMPONENT.'/'.'views'.'/'.'categories.php');
		FLItemsViewCategory::category( $row, $lists );
	}

	/**
	 * Save method
	 */
	function save()
	{
		global $mainframe;
		
		$error = "";
		
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_fl_items&view=categories' );
		// Initialize variables
		$db =& JFactory::getDBO();
		jimport('joomla.filesystem.file');

		$post	= JRequest::get( 'post' );
		
		if(empty($post['ordering'])) {
            $query = "SELECT MAX(ordering)+1 FROM #__fl_items_category";
            $db->setQuery($query);
            $newOrdering = $db->loadResult();
            $post['ordering'] = $newOrdering;
		}

		$nameChange = false;
		if($post['oldname'] && $post['oldname'] != $post['name']) {
			$nameChange = true;
		}
		
		$row =& JTable::getInstance('categories', 'FLItemsTable');
		
		if($post['item_category_id']) {
			$row->load($post['item_category_id']); 
		}
		
		if (!$row->bind( $post )) {
			return JError::raiseWarning( 500, $row->getError() );
		}

		if (!$row->check()) {
			return JError::raiseWarning( 500, $row->getError() );
		}

		if( $nameChange ) {
			$oldName = strtolower(JFilterOutput::stringURLSafe($post['oldname']));
			$newName = strtolower(JFilterOutput::stringURLSafe($post['name']));
			$oldPath = JPATH_COMPONENT_SITE."/templates/".$oldName;
			$newPath = JPATH_COMPONENT_SITE."/templates/".$newName;
			$types = array("detail", "results", "children");
			
			foreach($types as $t) {
				if(JFile::exists($oldPath."-$t.php")) {
					if(JFile::exists($newPath."-$t.php")) {
						$error = "Template rename error. File(s) may already exist.";
					} else {
						JFile::move($oldPath."-$t.php", $newPath."-$t.php");
					}
				}
			}
		}

		if (!$row->store()) {
			return JError::raiseWarning( $row->getError() );
		}

		$row->checkin();

		// Images
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');

		$allowedFileTypes = array('image/png', 'image/jpeg');
		$thisFolderPath = JPATH_ROOT . '/images/fl_items/';
		if (!JFolder::exists($thisFolderPath)) {
			JFolder::create($thisFolderPath);
		}

		// no-image
		$imageUpload = JRequest::getVar('newnoimage', null, 'files', 'array');
		if (isset($imageUpload) && in_array($imageUpload['type'], $allowedFileTypes)) {
			if($row->noimage) {
				JFile::delete($thisFolderPath . $row->noimage);
			}
			$this_filename = $row->item_category_id.'-noimage.' . strtolower(JFile::getExt($imageUpload['name'])); 
			JFile::upload($imageUpload['tmp_name'], $thisFolderPath . '/' . $this_filename);

			$row->noimage = $this_filename;
			if (!$row->store()) {
				return JError::raiseWarning( $row->getError() );
			}
		}
		
		// watermark
		$imageUpload = JRequest::getVar('newWatermarkImage', null, 'files', 'array');
		if (isset($imageUpload) && in_array($imageUpload['type'], $allowedFileTypes)) {
			if($row->watermarkImage) {
				JFile::delete($thisFolderPath . $row->watermarkImage);
			}
			$this_filename = $row->item_category_id.'-watermark.' . strtolower(JFile::getExt($imageUpload['name'])); 
			JFile::upload($imageUpload['tmp_name'], $thisFolderPath . '/' . $this_filename);

			$row->watermarkImage = $this_filename;
			if (!$row->store()) {
				return JError::raiseWarning( $row->getError() );
			}
		}
		
		// END images
		
		$task = JRequest::getCmd( 'task' );
		
		switch ($task)
		{
			case 'apply':
				$link = 'index.php?option=com_fl_items&view=categories&task=edit&item_category_id[]='. $row->item_category_id ;
				break;

			case 'save':
			default:
				$link = 'index.php?option=com_fl_items&view=categories';
				break;
		}
		if($error) {
			$this->setRedirect( $link, $error, "Notice" );
		} else {
			$this->setRedirect( $link, JText::_( 'Item Saved' ) );
		}
	}

	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_fl_items&view=categories' );

		// Initialize variables
		$db	=& JFactory::getDBO();
		$post	= JRequest::get( 'post' );
		$row    =& JTable::getInstance('categories', 'FLItemsTable');
		$row->bind( $post );
		$row->checkin();
	}

	function publish()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_fl_items&view=categories' );

		// Initialize variables
		$db		=& JFactory::getDBO();
		$user		=& JFactory::getUser();
		$item_category_id	= JRequest::getVar( 'item_category_id', array(), 'post', 'array' );
		$task		= JRequest::getCmd( 'task' );
		$publish	= ($task == 'publish');
		$n			= count( $item_category_id );

		if (empty( $item_category_id )) {
			return JError::raiseWarning( 500, JText::_( 'No items selected' ) );
		}

		JArrayHelper::toInteger( $item_category_id );
		$item_category_ids = implode( ',', $item_category_id);

		$query = 'UPDATE #__fl_items_category'
		. ' SET showCategory = ' . (int) $publish
		. ' WHERE item_category_id IN ( '. $item_category_ids .'  )'
		;
		$db->setQuery( $query );
		if (!$db->query()) {
			return JError::raiseWarning( 500, $db->getError() );
		}
		$this->setMessage( JText::sprintf( $publish ? 'Items published' : 'Items unpublished', $n ) );
	}


	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_fl_items&view=categories' );

		// Initialize variables
		$db		=& JFactory::getDBO();
		$item_category_id = JRequest::getVar( 'item_category_id', array(), 'post', 'array' );
		$n		= count( $item_category_id );
		JArrayHelper::toInteger( $item_category_id );

		if ($n)
		{
			$query = 'DELETE FROM #__fl_items_category'
			. ' WHERE item_category_id = ' . implode( ' OR item_category_id = ', $item_category_id )
			;
			$db->setQuery( $query );
			if (!$db->query()) {
				JError::raiseWarning( 500, $db->getError() );
			}

			$query = 'DELETE FROM #__fl_items_category'
			. ' WHERE item_category_id = ' . implode( ' OR item_category_id = ', $item_category_id )
			;
			$db->setQuery( $query );

			if (!$db->query()) {
				JError::raiseWarning( 500, $db->getError() );
			}
		}

		$this->setMessage( JText::sprintf( 'Items removed', $n ) );
	}
	
	function saveOrderAjax()
	{
		$db =& JFactory::getDBO();
		
		// Get the input
		$items = $this->input->post->get('item_category_id', array(), 'array');
		JArrayHelper::toInteger($items);
		
		// Get orders
		$sql = "SELECT ordering FROM #__fl_items_category WHERE item_category_id IN (".implode(",", $items).") ORDER BY ordering";
		$db->setQuery($sql);
		$ordering = $db->loadColumn();
		
		$c = 0;
		foreach($items as $i) {
			$sql = "UPDATE #__fl_items_category SET ordering = ".$ordering[$c]." WHERE item_category_id = $i";
			$db->setQuery($sql);
			$db->execute();
			$c++;
		}
		
		echo "1";
		JFactory::getApplication()->close();
	}
}
