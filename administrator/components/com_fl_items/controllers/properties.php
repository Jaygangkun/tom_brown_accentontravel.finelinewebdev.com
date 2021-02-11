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
class FLItemsControllerproperties extends JControllerAdmin 
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
		
		$categoryId = $this->input->get("categoryId", 0);
		if(empty($categoryId)) {
			$query = 'SELECT * FROM #__fl_items_category ORDER BY ordering';
			$db->setQuery( $query );
			$rows = $db->loadObjectList();
		
			require_once(JPATH_COMPONENT.'/'.'views'.'/'.'properties.php');
			FLItemsViewProperties::categorySelect( $rows );
			return;
		}

		$context			= 'com_fl_items.item.properties.list.';
		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order',		'p.ordering',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir',	'',			'word' );
		$filter_state		= $mainframe->getUserStateFromRequest( $context.'filter_state',		'filter_state',		'',			'word' );

		$limit		= 1000;
		$limitstart = $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0, 'int' );

		if ($filter_order == "p.ordering")
		{
		    $saveOrderingUrl = 'index.php?option=com_fl_items&view=properties&task=saveOrderAjax&tmpl=component';
		    JHtml::_('sortablelist.sortable', 'itemList', 'adminForm', strtolower($filter_order_Dir), $saveOrderingUrl);
		}

		$where = array();

		if ( $filter_state )
		{
			if ( $filter_state == 'P' ) {
				$where[] = 'p.enableProperty = 1';
			}
			else if ($filter_state == 'U' ) {
				$where[] = 'p.enableProperty = 0';
			}
		}
		
		$where[] = 'p.item_category_id = ' . $categoryId;

		$where		= count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '';
		// $orderby	= ' ORDER BY ordering ASC';
		$orderby	= ' ORDER BY '. $filter_order .' '. $filter_order_Dir;

		// get the total number of records
		$query = 'SELECT COUNT(*)'
		. ' FROM #__fl_items_properties AS p'
		. $where
		;
		$db->setQuery( $query );
		$total = $db->loadResult();

		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $total, $limitstart, $limit );

		$query = 'SELECT p.* '
		. ' FROM #__fl_items_properties AS p'
		. $where
		. $orderby
		;

		$db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
		$rows = $db->loadObjectList();
		
		// Category ID
		$lists['categoryId'] = $categoryId;
		
		// state filter
		$lists['state']	= JHTML::_('grid.state',  $filter_state );

		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;

		require_once(JPATH_COMPONENT.'/'.'views'.'/'.'properties.php');
		FLItemsViewProperties::properties( $rows, $pageNav, $lists );
	}

	function getToggle($name, $value) {
		$field = new JFormFieldRadio();
		$field->setup(new SimpleXMLElement('<field name="'.$name.'" type="radio" size="1" default="0" class="btn-group btn-group-yesno"><option value="0">JNO</option><option value="1">JYES</option></field>'), $value);
		return $field->renderField(array('hiddenLabel'=>true));
	}

	function edit()
	{
		JFormHelper::loadFieldClass('radio');
		
		$db	=& JFactory::getDBO();
		$user	=& JFactory::getUser();
	
		$task = JRequest::getCmd( 'task' );
		if ($task == 'edit') {
			$property_id	= JRequest::getVar('item_property_id', array(0), 'method', 'array'); 
			$property_id	= array((int) $property_id[0]);
		} else {
			$property_id	= array( 0 );
		}

		$option = JRequest::getCmd('option');

		$lists = array();

		$row =& JTable::getInstance('properties', 'FLItemsTable');
		$row->load( $property_id[0] );

		if ($property_id[0]) {
			$row->checkout( $user->get('id') );
		} else {
			$row->enableProperty = 1;
		}
		
		if($row->item_category_id == 0 && $this->input->post->get("item_category_id", 0) != 0) {
			$row->item_category_id = $this->input->post->get("item_category_id", 0);
		}
		
		// Get Item Types
		$query = 'SELECT *'
		. ' FROM #__fl_items_category'
		;
		$db->setQuery( $query );
		$getTypes = $db->loadObjectList();
		$lists['types'] = $getTypes;
		
		// Get Options
		$query = 'SELECT `option`'
		. ' FROM #__fl_items_option'
		. ' WHERE item_property_id = ' . $property_id[0]
		;
		$db->setQuery( $query );
		$getOptions = $db->loadColumn();
		
		$lists['options'] = $getOptions;
		
		// published
		$lists['enableProperty'] = FLItemsControllerproperties::getToggle("enableProperty", $row->enableProperty);
		$lists['showInDirectory'] = FLItemsControllerproperties::getToggle("showInDirectory", $row->showInDirectory);
		$lists['isSearchable'] = FLItemsControllerproperties::getToggle("isSearchable", $row->isSearchable);
		$lists['includeOnForm'] = FLItemsControllerproperties::getToggle("includeOnForm", $row->includeOnForm);
		$lists['allowUserEdit'] = FLItemsControllerproperties::getToggle("allowUserEdit", $row->allowUserEdit); 

		require_once(JPATH_COMPONENT.'/'.'views'.'/'.'properties.php');
		FLItemsViewProperties::property( $row, $lists );
	}

	/**
	 * Save method
	 */
	function save()
	{
		global $mainframe;
		
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_fl_items&view=properties' );
		// Initialize variables
		$db =& JFactory::getDBO();
		jimport('joomla.filesystem.file');

		$post = JRequest::get( 'post' );
		
		if(empty($post['ordering'])) {
			$query = "SELECT MAX(ordering)+1 FROM #__fl_items_properties";
			$db->setQuery($query);
			$newOrdering = $db->loadResult();
			$post['ordering'] = $newOrdering;
		}
		
		if(empty($post['caption'])) {
			$post['caption'] = $post['name'];
		}
		
		if(empty($post['name'])) {
			$post['name'] = str_replace(" ", "", strtolower($post['caption']));
		}
		
		if(!empty($post['name'])) {
			$post['name'] = str_replace(" ", "", strtolower($post['name']));
			$post['name'] = preg_replace('/[^A-Za-z0-9\-]/', '', $post['name']);
		}

		if(!empty($post['dimX']) && is_numeric($post['dimX']) && !empty($post['dimY']) && is_numeric($post['dimY']) ) {
			$post['dimensions'] = $post['dimX'] . "," . $post['dimY'];
		} else {
			$post['dimensions'] = "";
		}
		
		$row =& JTable::getInstance('properties', 'FLItemsTable');
		
		if (!$row->bind( $post )) {
			return JError::raiseWarning( 500, $row->getError() );
		}

		if (!$row->check()) {
			return JError::raiseWarning( 500, $row->getError() );
		}

		if (!$row->store()) {
			return JError::raiseWarning( $row->getError() );
		}

		$row->checkin();
		
		if(isset($post['options'])) {
			$newOptions = explode(",", $post['options']);
			
			// Clear old values
			$sql = "UPDATE #__fl_items_option SET needsDelete = 1 WHERE item_property_id = " . $row->item_property_id;
			$db->setQuery($sql);
			$db->execute();
			
			// Add new values
			foreach($newOptions as $option) {
				if(strlen($option) == 0){
					continue;
				}
				$sql = "SELECT COUNT(*) FROM #__fl_items_option WHERE item_property_id = " . $row->item_property_id . " AND `option` = '" . $option . "'";
				$db->setQuery($sql);
				$count = $db->loadResult();
				
				if($count == 0) {
					$sql = "INSERT INTO #__fl_items_option (item_property_id, `option`) VALUES(" . $row->item_property_id . ", '" . $option . "')";
					$db->setQuery($sql);
					$db->execute();
				} else {
					$sql = "UPDATE #__fl_items_option SET needsDelete = 0 WHERE item_property_id = " . $row->item_property_id . " AND `option` = '" . $option . "'";
					$db->setQuery($sql);
					$db->execute();
				}
			}
			
			// Delete removed values
			$sql = "DELETE FROM #__fl_items_option WHERE needsDelete = 1 AND item_property_id = " . $row->item_property_id;
			$db->setQuery($sql);
			$db->execute();
		}
		
		$task = JRequest::getCmd( 'task' );
		
		switch ($task)
		{
			case 'apply':
				$link = 'index.php?option=com_fl_items&view=properties&task=edit&item_property_id[]='. $row->item_property_id ;
				break;

			case 'save':
			default:
				$link = 'index.php?option=com_fl_items&view=properties&categoryId=' . $row->item_category_id ;
				break;
		}
		
		$this->setRedirect( $link, JText::_( 'Item Saved' ) );
	}

	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$post = JRequest::get( 'post' );

		$this->setRedirect( 'index.php?option=com_fl_items&view=properties&categoryId=' . $post['item_category_id'] );

		// Initialize variables
		$db	=& JFactory::getDBO();
		$post	= JRequest::get( 'post' );
		$row    =& JTable::getInstance('properties', 'FLItemsTable');
		$row->bind( $post );
		$row->checkin();
	}

	function publish()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_fl_items&view=properties' );

		// Initialize variables
		$db		=& JFactory::getDBO();
		$user		=& JFactory::getUser();
		$item_property_id	= JRequest::getVar( 'item_property_id', array(), 'post', 'array' );
		$task		= JRequest::getCmd( 'task' );
		$publish	= ($task == 'publish');
		$n			= count( $item_property_id );

		if (empty( $item_property_id )) {
			return JError::raiseWarning( 500, JText::_( 'No items selected' ) );
		}

		JArrayHelper::toInteger( $item_property_id );
		$item_property_ids = implode( ',', $item_property_id);

		$query = 'UPDATE #__fl_items_properties'
		. ' SET enableProperty = ' . (int) $publish
		. ' WHERE item_property_id IN ( '. $item_property_ids .'  )'
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

		$this->setRedirect( 'index.php?option=com_fl_items&view=properties' );

		// Initialize variables
		$db		=& JFactory::getDBO();
		$item_property_id = JRequest::getVar( 'item_property_id', array(), 'post', 'array' );
		$n		= count( $item_property_id );
		JArrayHelper::toInteger( $item_property_id );

		if ($n)
		{
			$query = 'DELETE FROM #__fl_items_properties'
			. ' WHERE item_property_id = ' . implode( ' OR item_property_id = ', $item_property_id )
			;
			$db->setQuery( $query );
			if (!$db->query()) {
				JError::raiseWarning( 500, $db->getError() );
			}

			$query = 'DELETE FROM #__fl_items_properties'
			. ' WHERE item_property_id = ' . implode( ' OR item_property_id = ', $item_property_id )
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
		$items = $this->input->post->get('item_property_id', array(), 'array');
		
		// Sanitize the input
		JArrayHelper::toInteger($items);
		
		// Get orders
		$sql = "SELECT ordering FROM #__fl_items_properties WHERE item_property_id IN (".implode(",", $items).") ORDER BY ordering";
		$db->setQuery($sql);
		$ordering = $db->loadColumn();
		
		$c = 0;
		foreach($items as $i) {
			$sql = "UPDATE #__fl_items_properties SET ordering = ".$ordering[$c]." WHERE item_property_id = $i";
			$db->setQuery($sql);
			$db->execute();
			$c++;
		}
		
		echo "1";

		// Close the application
		JFactory::getApplication()->close();
	}
	
}
