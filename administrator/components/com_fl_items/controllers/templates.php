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
class FLItemsControllertemplates extends JControllerAdmin
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

		$query = 'SELECT * FROM #__fl_items_category ORDER BY ordering';

		$db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
		$rows = $db->loadObjectList();
		
		$files = scandir(dirname(dirname(dirname(dirname(__DIR__))))."/components/com_fl_items/templates/");
		$lists['modules'] = array();
		foreach($files as $file) {
			if(strpos($file, "module-") === 0 ) {
				$lists['modules'][] = $file;
			}
		}
		
		require_once(JPATH_COMPONENT.'/'.'views'.'/'.'templates.php');
		FLItemsViewTemplate::templates( $rows, $pageNav, $lists );
	}
	
	function editResults() {
		
		$db =& JFactory::getDBO();
		$categoryId = JRequest::getVar('categoryId');
		
		$query = 'SELECT * FROM #__fl_items_category WHERE item_category_id = ' . $categoryId;
		$db->setQuery( $query );
		$row = $db->loadObject();
		
		$typeName = JFilterOutput::stringURLSafe(strtolower($row->name));
		$filename = "$typeName-results.php";
		
		$path = dirname(dirname(dirname(dirname(__DIR__))))."/components/com_fl_items/templates/$filename";
		if(file_exists($path)) {
			$lists['data'] = file_get_contents($path);
		} else {
			$lists['data'] = "";
		}
		
		$query = 'SELECT * FROM #__fl_items_category WHERE parent_category_id = ' . $categoryId;
		$db->setQuery( $query );
		$lists['children'] = $db->loadObjectList();
		
		$query = 'SELECT * FROM #__fl_items_properties WHERE item_category_id = ' . $categoryId . " ORDER BY ordering";
		$db->setQuery( $query );
		$lists['properties'] = $db->loadObjectList();
		
		require_once(JPATH_COMPONENT.'/'.'views'.'/'.'templates.php');
		FLItemsViewTemplate::editTemplate( $row, $lists, $filename );
	}
	
	function editDetail() {
		
		$db =& JFactory::getDBO();
		$categoryId = JRequest::getVar('categoryId');
		
		$query = 'SELECT * FROM #__fl_items_category WHERE item_category_id = ' . $categoryId;
		$db->setQuery( $query );
		$row = $db->loadObject();
		
		$typeName = JFilterOutput::stringURLSafe(strtolower($row->name));
		$filename = "$typeName-detail.php";
		
		$path = dirname(dirname(dirname(dirname(__DIR__))))."/components/com_fl_items/templates/$filename";
		if(file_exists($path)) {
			$lists['data'] = file_get_contents($path);
		} else {
			$lists['data'] = "";
		}
		
		$query = 'SELECT * FROM #__fl_items_category WHERE parent_category_id = ' . $categoryId;
		$db->setQuery( $query );
		$lists['children'] = $db->loadObjectList();
		
		$query = 'SELECT * FROM #__fl_items_properties WHERE item_category_id = ' . $categoryId . " ORDER BY ordering";
		$db->setQuery( $query );
		$lists['properties'] = $db->loadObjectList();
		
		require_once(JPATH_COMPONENT.'/'.'views'.'/'.'templates.php');
		FLItemsViewTemplate::editTemplate( $row, $lists, $filename );
	}
	
	function editLinks() {
		
		$db =& JFactory::getDBO();
		$categoryId = JRequest::getVar('categoryId');
		
		$query = 'SELECT * FROM #__fl_items_category WHERE item_category_id = ' . $categoryId;
		$db->setQuery( $query );
		$row = $db->loadObject();
		
		$filename = "id-$categoryId-links.php";
		
		$path = dirname(dirname(dirname(dirname(__DIR__))))."/components/com_fl_items/templates/$filename";
		if(file_exists($path)) {
			$lists['data'] = file_get_contents($path);
		} else {
			$lists['data'] = "";
		}
		
		$query = 'SELECT * FROM #__fl_items_category WHERE parent_category_id = ' . $categoryId;
		$db->setQuery( $query );
		$lists['children'] = $db->loadObjectList();
		
		$query = 'SELECT * FROM #__fl_items_properties WHERE item_category_id = ' . $categoryId . " ORDER BY ordering";
		$db->setQuery( $query );
		$lists['properties'] = $db->loadObjectList();
		
		require_once(JPATH_COMPONENT.'/'.'views'.'/'.'templates.php');
		FLItemsViewTemplate::editTemplate( $row, $lists, $filename );
	}
	
	function editChildren() {
		
		$db =& JFactory::getDBO();
		$categoryId = JRequest::getVar('categoryId');
		
		$query = 'SELECT * FROM #__fl_items_category WHERE item_category_id = ' . $categoryId;
		$db->setQuery( $query );
		$row = $db->loadObject();
		
		$typeName = JFilterOutput::stringURLSafe(strtolower($row->name));
		$filename = "$typeName-children.php";
		
		$path = dirname(dirname(dirname(dirname(__DIR__))))."/components/com_fl_items/templates/$filename";
		if(file_exists($path)) {
			$lists['data'] = file_get_contents($path);
		} else {
			$lists['data'] = "";
		}
		
		$query = 'SELECT * FROM #__fl_items_category WHERE parent_category_id = ' . $categoryId;
		$db->setQuery( $query );
		$lists['children'] = $db->loadObjectList();
		
		$query = 'SELECT * FROM #__fl_items_properties WHERE item_category_id = ' . $categoryId . " ORDER BY ordering";
		$db->setQuery( $query );
		$lists['properties'] = $db->loadObjectList();
		
		require_once(JPATH_COMPONENT.'/'.'views'.'/'.'templates.php');
		FLItemsViewTemplate::editTemplate( $row, $lists, $filename );
	}
	
	function editNoResults() {
		
		$db =& JFactory::getDBO();
		$categoryId = JRequest::getVar('categoryId');
		
		$query = 'SELECT * FROM #__fl_items_category WHERE item_category_id = ' . $categoryId;
		$db->setQuery( $query );
		$row = $db->loadObject();
		
		$filename = "$categoryId-no-results.php";
		
		$path = dirname(dirname(dirname(dirname(__DIR__))))."/components/com_fl_items/templates/$filename";
		if(file_exists($path)) {
			$lists['data'] = file_get_contents($path);
		} else {
			$lists['data'] = "";
		}
		
		$query = 'SELECT * FROM #__fl_items_category WHERE parent_category_id = ' . $categoryId;
		$db->setQuery( $query );
		$lists['children'] = $db->loadObjectList();
		
		$query = 'SELECT * FROM #__fl_items_properties WHERE item_category_id = ' . $categoryId . " ORDER BY ordering";
		$db->setQuery( $query );
		$lists['properties'] = $db->loadObjectList();
		
		require_once(JPATH_COMPONENT.'/'.'views'.'/'.'templates.php');
		FLItemsViewTemplate::editTemplate( $row, $lists, $filename );
	}
	
	function editModule() {
		
		$db =& JFactory::getDBO();
		$moduleName = JRequest::getVar('module');
		
		// $query = 'SELECT * FROM #__fl_items_category WHERE item_category_id = ' . $categoryId;
		// $db->setQuery( $query );
		// $row = $db->loadObject();
		
		// $typeName = JFilterOutput::stringURLSafe(strtolower($row->name));
		$filename = "module-$moduleName.php";
		
		$path = dirname(dirname(dirname(dirname(__DIR__))))."/components/com_fl_items/templates/$filename";
		// if(!file_exists($path)) {
			// touch($path); // create file if we need it!
		// }
		$lists['data'] = file_get_contents($path);
		
		// $query = 'SELECT * FROM #__fl_items_category WHERE parent_category_id = ' . $categoryId;
		// $db->setQuery( $query );
		$lists['children'] = array();
		
		// $query = 'SELECT * FROM #__fl_items_properties WHERE item_category_id = ' . $categoryId . " ORDER BY ordering";
		// $db->setQuery( $query );
		$lists['properties'] = array();
		
		require_once(JPATH_COMPONENT.'/'.'views'.'/'.'templates.php');
		FLItemsViewTemplate::editTemplate( $row, $lists, $filename );
	}

	function newModule() {
		global $mainframe;
		
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_fl_items&view=templates' );
		// Initialize variables
		$db =& JFactory::getDBO();
		jimport('joomla.filesystem.file');

		$post = JRequest::get( 'post' );
		
		$newFileName = $post['newModuleName'];
		$newFileName = str_replace("module-", "", str_replace(".php", "", $newFileName));
		$newFileName = preg_replace('/[^A-Za-z0-9\-]/', '', $newFileName);
		
		if($newFileName) {
			$path = dirname(dirname(dirname(dirname(__DIR__))))."/components/com_fl_items/templates/module-$newFileName.php";
			if(!file_exists($path)) {
				touch($path); // create file if we need it!
			}
		}
		
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

		$row =& JTable::getInstance('templates', 'FLItemsTable');
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

		require_once(JPATH_COMPONENT.'/'.'views'.'/'.'templates.php');
		FLItemsViewTemplate::category( $row, $lists );
	}

	/**
	 * Save method
	 */
	function save()
	{
		global $mainframe;
		
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_fl_items&view=templates' );
		// Initialize variables
		$db =& JFactory::getDBO();
		jimport('joomla.filesystem.file');

		$post = JRequest::get( 'post' );
		
		$file = $post['filename'];
		$filePath = dirname(dirname(dirname(dirname(__DIR__))))."/components/com_fl_items/templates/$file";
		if(!file_exists($filePath)) {
			touch($filePath); // create file if we need it!
		} 
		
		// write to file
		$content = $_POST['templateText'];
		
		$handle = fopen($filePath, 'w') or die('Cannot open file:  '.$filePath);
		fwrite($handle, $content);
		fclose($handle);

		$task = JRequest::getCmd( 'task' );
		$type = ucfirst($post['type']);
		
		switch ($task)
		{
			case 'apply':
				if($type == "Module") {
					$module = str_replace("module-", "", str_replace(".php", "", $file));
					$link = 'index.php?option=com_fl_items&view=templates&task=edit'.$type.'&module='. $module;
				} else {
					$link = 'index.php?option=com_fl_items&view=templates&task=edit'.$type.'&categoryId='. $post['categoryId'];
				}
				break;

			case 'save':
			default:
				$link = 'index.php?option=com_fl_items&view=templates';
				break;
		}
		$this->setRedirect( $link, JText::_( 'Item Saved' ) );
	}

	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_fl_items&view=templates' );
	}

	function publish()
	{
	}


	function remove()
	{
	}
	
	
	function saveOrderAjax()
	{
	}
}
