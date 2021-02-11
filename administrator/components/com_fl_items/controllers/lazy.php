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
class FLItemsControllerlazy extends JControllerAdmin
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

		$query = 'SELECT * FROM #__fl_items_category';

		$db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
		$rows = $db->loadObjectList();
		
		// Get Item Types
		$query = 'SELECT *'
		. ' FROM #__fl_items_category'
		;
		$db->setQuery( $query );
		$getTypes = $db->loadObjectList();
		$lists['types'] = $getTypes;
		
		require_once(JPATH_COMPONENT.'/'.'views'.'/'.'lazy.php');
		FLItemsViewLazy::display( $rows, $pageNav, $lists );
	}
	
	function create()
	{
		$db =& JFactory::getDBO();
		
		$newStuff = json_decode($_POST['all-dat-data']);
		
		// Get new Category ordering
		$query = 'SELECT MAX(ordering) FROM #__fl_items_category';
		$db->setQuery( $query );
		$newCategoryOrdering = $db->loadResult();
		if(is_numeric($newCategoryOrdering)) {
			$newCategoryOrdering += 1;
		} else {
			$newCategoryOrdering = 1;
		}
		
		// Get new Property ordering
		$query = 'SELECT MAX(ordering) FROM #__fl_items_properties';
		$db->setQuery( $query );
		$newPropertyOrdering = $db->loadResult();
		if(is_numeric($newPropertyOrdering)) {
			$newPropertyOrdering += 1;
		} else {
			$newPropertyOrdering = 1;
		}
		
		foreach($newStuff as $newType) {
			$typeName = $newType[0];
			if($typeName) {
				$typeProps = $newType[1];
				$typeImages = $newType[2];
				
				$row =& JTable::getInstance('categories', 'FLItemsTable');
				$row->name = $typeName;
				$row->ordering = $newCategoryOrdering;
				$newCategoryOrdering++; // increase for next one
				
				if($typeImages == 0) {
					$row->hasImages = 0;
					$row->isSingleImage = 0;
				} else if($typeImages == 1) {
					$row->hasImages = 1;
					$row->isSingleImage = 1;
				} else if($typeImages == 2) {
					$row->hasImages = 1;
					$row->isSingleImage = 0;
				}
				
				if (!$row->store()) {
					return JError::raiseWarning( $row->getError() );
				}
				
				foreach($typeProps as $newProp) {
					$propCaption = $newProp[0];
					$propName = $propCaption;
					$propType = $newProp[1];
					if($propName) {
						echo "$typeName :: $propName - $propType <br>";
						
						$propName = str_replace(" ", "", strtolower($propName));
						$propName = str_replace(" ", "", strtolower($propName));
						$propName = preg_replace('/[^A-Za-z0-9\-]/', '', $propName);
				
						$propRow =& JTable::getInstance('properties', 'FLItemsTable');
						$propRow->name = $propName;
						$propRow->caption = $propCaption;
						$propRow->type = $propType;
						$propRow->item_category_id = $row->item_category_id;
						$propRow->ordering = $newPropertyOrdering;
						$newPropertyOrdering++;
						
						if (!$propRow->store()) {
							return JError::raiseWarning( $propRow->getError() );
						}
					}
				}
			}
		}

		$link = 'index.php?option=com_fl_items';
		$this->setRedirect( $link, JText::_( 'Lazy Mode Complete' ) );
	}
}
