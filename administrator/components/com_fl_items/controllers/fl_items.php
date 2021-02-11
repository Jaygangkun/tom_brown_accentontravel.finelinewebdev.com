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


use SimpleExcel\SimpleExcel;

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

/**
 * @package		Joomla
 * @subpackage	Banners
 */
class FLItemsControllerfl_items extends JControllerAdmin {
	/**
	 * Constructor
	 */
	function __construct($config = array()) {
		parent::__construct($config);
		// Register Extra tasks
		$this->registerTask('add', 'edit');
		$this->registerTask('apply', 'save');
		$this->registerTask('unpublish', 'publish');
	}

	function display() {
		$app = JFactory::getApplication();
		$db = &JFactory::getDBO();

		$context = 'com_fl_items.item.list.';
		$filter_order = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'i.ordering', 'cmd');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '', 'word');
		$filter_state = $app->getUserStateFromRequest($context . 'filter_state', 'filter_state', '', 'word');

		$limit = $app->getUserStateFromRequest($context . 'filter_limit', 'limit', '20', 'int');
		$limitstart = $app->getUserStateFromRequest($context . 'limitstart', 'limitstart', 0, 'int');

		$search = $app->getUserStateFromRequest($context . 'limit', 'search', '', 'text');

		$categoryId = $this->input->get("item_category_id", 0);

		$db = &JFactory::getDBO();
		$query = 'SELECT * FROM #__fl_items_category WHERE showCategory = 1 AND isSubItem = 0';
		$db->setQuery($query);
		$types = $db->loadObjectList();

		if (count($types) == 0) {
			require_once JPATH_COMPONENT . '/views/items.php';
			FLItemsViewItem::welcome();
		} else if ($categoryId == 0) {
			require_once JPATH_COMPONENT . '/views/items.php';
			FLItemsViewItem::selectInstructions();
		} else {
			if (empty($categoryId)) {
				// $categoryId = $types[0]->item_category_id;
			}

			if ($filter_order == "i.ordering") {
				$saveOrderingUrl = 'index.php?option=com_fl_items&task=saveOrderAjax&tmpl=component';
				JHtml::_('sortablelist.sortable', 'itemList', 'adminForm', strtolower($filter_order_Dir), $saveOrderingUrl);
			}

			$propSort = false;
			if (strpos($filter_order, "prop.") !== false) {
				$propSort = true;
				$sortByProp = substr($filter_order, 5);
				$query = "SELECT item_property_id FROM #__fl_items_properties WHERE `name` = '" . $sortByProp . "' AND item_category_id = " . $categoryId;
				$db->setQuery($query);
				$propSortId = $db->loadResult();
				if (empty($propSortId)) {
					$propSort = false;
				}
			}

			$where = array();

			if ($filter_state) {
				if ($filter_state == 'P') {
					$where[] = 'i.showItem = 1';
				} else if ($filter_state == 'U') {
					$where[] = 'i.showItem = 0';
				}
			}

			$where[] = 'i.item_category_id = ' . $categoryId;
			$where[] = 'i.name LIKE "%' . $search . '%"';

			$where = count($where) ? ' WHERE ' . implode(' AND ', $where) : '';
			if ($propSort && $propSortId) {
				$orderby = ' ORDER BY sorter.`value` ' . $filter_order_Dir . ', i.item_category_id';
			} else {
				if (strpos($filter_order, "prop.") !== false) {
					$filter_order = 'i.ordering';
				}
				$orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir . ', i.item_category_id';
			}

			// get the total number of records
			$query = 'SELECT COUNT(*)'
				. ' FROM #__fl_items AS i'
				. $where
			;
			$db->setQuery($query);
			$total = $db->loadResult();

			jimport('joomla.html.pagination');
			$pageNav = new JPagination($total, $limitstart, $limit);

			$query = 'SELECT i.*, pc.name AS itemCategoryName, parent.name AS parentName, ppc.item_category_id as parentCategoryId, ppc.name as parentCategoryName '
				. ' FROM #__fl_items AS i'
				. ' LEFT JOIN #__fl_items_category as pc ON pc.item_category_id = i.item_category_id '
				. ' LEFT JOIN #__fl_items_category as ppc ON ppc.item_category_id = pc.parent_category_id '
				. ' LEFT JOIN #__fl_items as parent ON parent.item_id = i.parent_item_id ';
			if ($propSort) {
				$query .= ' LEFT JOIN #__fl_items_item_property sorter ON sorter.item_id = i.item_id AND sorter.item_property_id = ' . $propSortId . ' ';
			}
			$query .= $where . $orderby;
			
			$db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
			$rows = $db->loadObjectList();

			$idList = "0";
			$lists['hasParent'] = false;
			foreach ($rows as $row) {
				$idList .= "," . $row->item_id;
				if($row->parentName) {
					$lists['hasParent'] = true;
					$lists['parentName'] = $row->parentName;
				}
			}
			
			// Get parent category info
			$query = "SELECT c.* 
				FROM #__fl_items_category c 
				WHERE c.item_category_id = (
					SELECT parent_category_id FROM #__fl_items_category WHERE item_category_id = $categoryId
				)";
			$db->setQuery($query);
			$parentInfo = $db->loadObject();
			if($parentInfo) {
				$lists['hasParent'] = true;
				$lists['parentCategoryId'] = $parentInfo->item_category_id;
				$lists['parentCategoryName'] = $parentInfo->name;
			}

			// Get properties
			$query = "
				SELECT ip.*, p.`name`, p.caption, p.type, (SELECT io.option FROM #__fl_items_option io WHERE io.item_property_multi_id = ip.value) AS selectValue
					, (SELECT li.name FROM #__fl_items li WHERE li.item_id = ip.value) AS selectItemValue
				FROM #__fl_items_item_property ip
				INNER JOIN #__fl_items_properties p ON p.item_property_id = ip.item_property_id AND p.showInDirectory = 1
				WHERE 1 = (SELECT COUNT(*) FROM #__fl_items i WHERE i.item_id = ip.item_id)
					AND ip.item_id IN ($idList)
			";
			$db->setQuery($query);
			$getProps = $db->loadObjectList();

			$propData = array();
			$props = array();
			$foundProps = array();

			foreach ($getProps as $prop) {
				if (!in_array($prop->name, $foundProps)) {
					$props[] = array("caption" => $prop->caption, "name" => $prop->name);
					$foundProps[] = $prop->name;
				}
				$propData[$prop->item_id][$prop->caption] = $prop;
			}

			$lists['props'] = $props;
			$lists['propdata'] = $propData;

			// category ID
			$lists['item_category_id'] = $categoryId;

			$query = "
				SELECT c.isFeaturedEnabled, c.isDescriptionEnabled, c.name, c.description, c.usersEditOnly
				FROM #__fl_items_category c
				WHERE c.item_category_id = $categoryId
			";
			$db->setQuery($query);
			$getCategory = $db->loadObject();
			$lists['isFeaturedEnabled'] = $getCategory->isFeaturedEnabled;
			$lists['isDescriptionEnabled'] = $getCategory->isDescriptionEnabled;
			$lists['categoryDescription'] = $getCategory->description;
			$lists['categoryName'] = $getCategory->name;
			$lists['usersEditOnly'] = $getCategory->usersEditOnly;

			$limitVals = array(
				array('num' => 10, 'txt' => '10'),
				array('num' => 20, 'txt' => '20'),
				array('num' => 50, 'txt' => '50'),
				array('num' => 100, 'txt' => '100'),
				array('num' => 99999, 'txt' => 'all'),
			);

			// state filter
			$lists['state'] = JHTML::_('grid.state', $filter_state);
			$lists['limit'] = JHTML::_('select.genericlist', $limitVals, 'limit', 'style="width: 75px;"', 'num', 'txt', $limit);

			$lists['search'] = $search;

			// table ordering
			$lists['order_Dir'] = $filter_order_Dir;
			$lists['order'] = $filter_order;

			require_once JPATH_COMPONENT . '/views/items.php';
			FLItemsViewItem::items($rows, $pageNav, $lists);
		}
	}

	function edit() {
		$db = &JFactory::getDBO();
		$user = &JFactory::getUser();

		$task = JRequest::getCmd('task');
		if ($task == 'edit') {
			$item_id = JRequest::getVar('item_id', array(0), 'method', 'array');
			$item_id = array((int) $item_id[0]);
		} else {
			$item_id = array(0);
		}

		$option = JRequest::getCmd('option');

		$lists = array();

		$row = &JTable::getInstance('items', 'FLItemsTable');
		$row->load($item_id[0]);

		if ($item_id[0]) {
			$isUpdate = true;
			$row->checkout($user->get('id'));
		} else {
			$isUpdate = false;
			$row->showItem = 1;
		}

		if (empty($row->item_category_id)) {
			$row->item_category_id = $this->input->get("item_category_id", 0);
		}

		// Get Categories
		$sql = 'SELECT item_category_id, name, subItemParentId '
			. ' FROM #__fl_items_category'
		;
		$db->setQuery($sql);
		if (!$db->query()) {
			return JError::raiseWarning(500, $db->getErrorMsg());
		}
		$itemCategoryList = $db->loadObjectList();
		$lists['itemCategory'] = JHTML::_('select.genericlist', $itemCategoryList, 'item_category_id', 'class="inputbox" size="1" readonly="readonly"', 'item_category_id', 'name', $row->item_category_id);

		// Get images
		$query = 'SELECT pi.* '
			. ' FROM #__fl_items_image AS pi'
			. ' WHERE item_id = ' . $item_id[0]
			. ' ORDER BY pi.ordering, pi.filename'
		;
		$db->setQuery($query);
		$lists['itemImage'] = $db->loadObjectList();

		// Get Category Data
		$query = 'SELECT * '
		. ' FROM #__fl_items_category WHERE item_category_id = ' . $row->item_category_id
		;
		$db->setQuery($query);
		$lists['categoryData'] = $db->loadObject();

		if($lists['categoryData']->parent_category_id) {
			$query = 'SELECT item_id, name '
			. ' FROM #__fl_items WHERE item_category_id = ' . $lists['categoryData']->parent_category_id . ' ORDER BY name'
			;
			$db->setQuery($query);
			$parentItems = $db->loadObjectList();
			$lists['parentSelect'] = JHTML::_('select.genericlist', $parentItems, 'parent_item_id', 'class="inputbox" size="1"','item_id', 'name', $row->parent_item_id );
		} else {
			$lists['parentSelect'] = null;
		}

		// Get Properties
		$query = 'SELECT p.* '
		. ' FROM #__fl_items_properties AS p'
		. ' WHERE p.enableProperty = 1'
		. ' AND p.item_category_id = ' . $row->item_category_id
			. ' ORDER BY p.ordering'
		;
		$db->setQuery($query);
		$getProperties = $db->loadObjectList();

		$links = array();
		$subs = array();

		foreach ($getProperties as $prop) {
			// check for links
			if (substr($prop->type, 0, 5) == "link-") {
				$newLink = substr($prop->type, 5);
				if (!in_array($newLink, $links)) {
					$links[] = array("link" => $newLink, "prop" => $prop->item_property_id);
				}
			}
			if (substr($prop->type, 0, 6) == "mlink-") {
				$newLink = substr($prop->type, 6);
				if (!in_array($newLink, $links)) {
					$links[] = array("link" => $newLink, "prop" => $prop->item_property_id);
				}
			}
			// Check for sub-items
			if (substr($prop->type, 0, 5) == "subs-") {
				$newSub = substr($prop->type, 5);
				if (!in_array($newSub, $subs)) {
					$parentCategoryId = 0;
					foreach ($itemCategoryList as $cat) {
						if ($cat->item_category_id == $newSub) {
							$parentCategoryId = $cat->subItemParentId;
						}
					}
					$subs[] = array("subs" => $newSub, "prop" => $prop->item_property_id, "parent" => $parentCategoryId);
				}
			}
		}
		$lists['properties'] = $getProperties;

		$cleanSelectedOptions = array();
		$cleanOptions = array();

		// Get any linked options if needed
		foreach ($links as $link) {
			$query = 'SELECT i.name AS `option`, i.item_id AS item_property_multi_id '
				. ' FROM #__fl_items AS i'
				. ' WHERE i.showItem = 1'
				. ' AND i.item_category_id = ' . $link['link']
				. ' ORDER BY i.name'
			;
			$db->setQuery($query);
			$linkedProperties = $db->loadObjectList();
			foreach ($linkedProperties as $prop) {
				$cleanOptions[$link['prop']][] = $prop;
			}
		}

		// Get any Sub-Items
		foreach ($subs as $sub) {
			// Sub-item properties
			$query = 'SELECT * '
				. ' FROM #__fl_items_properties'
				. ' WHERE enableProperty = 1'
				. ' AND item_category_id = ' . $sub['subs']
				. ' ORDER BY ordering'
			;
			$db->setQuery($query);
			$subProperties = $db->loadObjectList();

			foreach ($subProperties as $sp) {
				$cleanOptions[$sub['prop']]['properties'][] = $sp;
			}

			// Sub-item "items"/options
			$query = 'SELECT * '
				. ' FROM #__fl_items'
				. ' WHERE showItem = 1'
				. ' AND item_category_id = ' . $sub['parent']
				. ' ORDER BY ordering'
			;
			$db->setQuery($query);
			$subItems = $db->loadObjectList();

			foreach ($subItems as $si) {
				$cleanOptions[$sub['prop']]['items'][] = $si;
			}
		}
		if (count($subs) && $row->item_id) {
			// Sub-item values
			$query = 'SELECT ip.*, p.name, isi.sub_item_id, isi.sub_item_parent_id '
			. ' FROM #__fl_items_item_sub_item isi'
			. ' INNER JOIN #__fl_items_item_property ip'
			. ' ON ip.item_id = isi.sub_item_id'
			. ' INNER JOIN #__fl_items_properties p'
			. ' ON p.item_property_id = ip.item_property_id'
			. ' WHERE isi.item_id = ' . $row->item_id
			;
			$db->setQuery($query);
			$getSubValues = $db->loadObjectList();
			$subValues = array();

			foreach ($getSubValues as $sv) {
				$subValues[$sv->sub_item_parent_id][$sv->name] = $sv;
			}

			$lists['subValues'] = $subValues;
		}

		// Get Options
		$query = 'SELECT o.* '
			. ' FROM #__fl_items_option AS o'
		;
		$db->setQuery($query);
		$options = $db->loadObjectList();
		foreach ($options as $opt) {
			$cleanOptions[$opt->item_property_id][] = $opt;
		}

		$lists['options'] = $cleanOptions;

		// Get Property Values
		$query = 'SELECT p.* '
			. ' FROM #__fl_items_item_property AS p'
			. ' WHERE p.item_id = ' . $item_id[0]
		;
		$db->setQuery($query);
		$getPropValues = $db->loadObjectList();
		$propValues = array();
		foreach ($getPropValues as $prop) {
			$propValues[$prop->item_property_id] = $prop->value;
		}
		$lists['propertyValues'] = $propValues;

		// published
		$lists['showItem'] = JHTML::_('select.booleanlist', 'showItem', '', $row->showItem);

		// published
		$lists['isFeatured'] = JHTML::_('select.booleanlist', 'isFeatured', '', $row->isFeatured);

		require_once JPATH_COMPONENT . '/views/items.php';
		FLItemsViewItem::item($row, $lists);
	}

	function batchAdd() {
		$db = &JFactory::getDBO();
		$user = &JFactory::getUser();

		$task = JRequest::getCmd('task');
		$item_id = array(0);

		$option = JRequest::getCmd('option');

		$lists = array();

		$row = &JTable::getInstance('items', 'FLItemsTable');
		$row->load($item_id[0]);

		if ($item_id[0]) {
			$isUpdate = true;
			$row->checkout($user->get('id'));
		} else {
			$isUpdate = false;
			$row->showItem = 1;
		}

		if (empty($row->item_category_id)) {
			$row->item_category_id = $this->input->get("item_category_id", 0);
		}

		// Get images
		$query = 'SELECT pi.* '
			. ' FROM #__fl_items_image AS pi'
			. ' WHERE item_id = ' . $item_id[0]
			. ' ORDER BY pi.ordering, pi.filename'
		;
		$db->setQuery($query);
		$lists['itemImage'] = $db->loadObjectList();

		// Get images
		$query = 'SELECT hasImages '
		. ' FROM #__fl_items_category WHERE item_category_id = ' . $row->item_category_id
		;
		$db->setQuery($query);
		$lists['hasImages'] = $db->loadObject()->hasImages;

		// Get single Image
		$query = 'SELECT isSingleImage '
		. ' FROM #__fl_items_category WHERE item_category_id = ' . $row->item_category_id
		;
		$db->setQuery($query);
		$lists['isSingleImage'] = $db->loadObject()->isSingleImage;

		// Get Properties
		$query = 'SELECT p.* '
		. ' FROM #__fl_items_properties AS p'
		. ' WHERE p.enableProperty = 1'
		. ' AND p.item_category_id = ' . $row->item_category_id
			. ' ORDER BY p.ordering'
		;
		$db->setQuery($query);
		$getProperties = $db->loadObjectList();

		$links = array();
		$subs = array();

		foreach ($getProperties as $prop) {
			// check for links
			if (substr($prop->type, 0, 5) == "link-") {
				$newLink = substr($prop->type, 5);
				if (!in_array($newLink, $links)) {
					$links[] = array("link" => $newLink, "prop" => $prop->item_property_id);
				}
			}
			if (substr($prop->type, 0, 6) == "mlink-") {
				$newLink = substr($prop->type, 6);
				if (!in_array($newLink, $links)) {
					$links[] = array("link" => $newLink, "prop" => $prop->item_property_id);
				}
			}
			// Check for sub-items
			if (substr($prop->type, 0, 5) == "subs-") {
				$newSub = substr($prop->type, 5);
				if (!in_array($newSub, $subs)) {
					$parentCategoryId = 0;
					foreach ($itemCategoryList as $cat) {
						if ($cat->item_category_id == $newSub) {
							$parentCategoryId = $cat->subItemParentId;
						}
					}
					$subs[] = array("subs" => $newSub, "prop" => $prop->item_property_id, "parent" => $parentCategoryId);
				}
			}
		}
		$lists['properties'] = $getProperties;

		$cleanSelectedOptions = array();
		$cleanOptions = array();

		// Get any linked options if needed
		foreach ($links as $link) {
			$query = 'SELECT i.name AS `option`, i.item_id AS item_property_multi_id '
				. ' FROM #__fl_items AS i'
				. ' WHERE i.showItem = 1'
				. ' AND i.item_category_id = ' . $link['link']
				. ' ORDER BY i.name'
			;
			$db->setQuery($query);
			$linkedProperties = $db->loadObjectList();
			foreach ($linkedProperties as $prop) {
				$cleanOptions[$link['prop']][] = $prop;
			}
		}

		// Get any Sub-Items
		foreach ($subs as $sub) {
			// Sub-item properties
			$query = 'SELECT * '
				. ' FROM #__fl_items_properties'
				. ' WHERE enableProperty = 1'
				. ' AND item_category_id = ' . $sub['subs']
				. ' ORDER BY ordering'
			;
			$db->setQuery($query);
			$subProperties = $db->loadObjectList();

			foreach ($subProperties as $sp) {
				$cleanOptions[$sub['prop']]['properties'][] = $sp;
			}

			// Sub-item "items"/options
			$query = 'SELECT * '
				. ' FROM #__fl_items'
				. ' WHERE showItem = 1'
				. ' AND item_category_id = ' . $sub['parent']
				. ' ORDER BY ordering'
			;
			$db->setQuery($query);
			$subItems = $db->loadObjectList();

			foreach ($subItems as $si) {
				$cleanOptions[$sub['prop']]['items'][] = $si;
			}
		}
		if (count($subs) && $row->item_id) {
			// Sub-item values
			$query = 'SELECT ip.*, p.name, isi.sub_item_id, isi.sub_item_parent_id '
			. ' FROM #__fl_items_item_sub_item isi'
			. ' INNER JOIN #__fl_items_item_property ip'
			. ' ON ip.item_id = isi.sub_item_id'
			. ' INNER JOIN #__fl_items_properties p'
			. ' ON p.item_property_id = ip.item_property_id'
			. ' WHERE isi.item_id = ' . $row->item_id
			;
			$db->setQuery($query);
			$getSubValues = $db->loadObjectList();
			$subValues = array();

			foreach ($getSubValues as $sv) {
				$subValues[$sv->sub_item_parent_id][$sv->name] = $sv;
			}

			$lists['subValues'] = $subValues;
		}

		if ($isUpdate) {
			// Get Selected Options
			// $query = 'SELECT o.* '
			// . ' FROM #__fl_items_option_map AS o'
			// . ' WHERE item_id = ' . $row->item_id;
// 
			// $db->setQuery($query);
			// $selectedOptions = $db->loadObjectList();
			// foreach ($selectedOptions AS $selectOpt) {
				// $cleanSelectedOptions[$selectOpt->item_property_id][] = $selectOpt->item_property_multi_id;
			// }
		}

		// Get Options
		$query = 'SELECT o.* '
			. ' FROM #__fl_items_option AS o'
		;
		$db->setQuery($query);
		$options = $db->loadObjectList();
		foreach ($options as $opt) {
			$opt->selected = "";
			if (isset($cleanSelectedOptions[$opt->item_property_id]) && in_array($opt->item_property_multi_id, $cleanSelectedOptions[$opt->item_property_id])) {
				$opt->selected = "selected";
			}
			$cleanOptions[$opt->item_property_id][] = $opt;
		}

		$lists['options'] = $cleanOptions;

		// Get Property Values
		$query = 'SELECT p.* '
			. ' FROM #__fl_items_item_property AS p'
			. ' WHERE p.item_id = ' . $item_id[0]
		;
		$db->setQuery($query);
		$getPropValues = $db->loadObjectList();
		$propValues = array();
		foreach ($getPropValues as $prop) {
			$propValues[$prop->item_property_id] = $prop->value;
		}
		$lists['propertyValues'] = $propValues;

		// published
		$lists['showItem'] = JHTML::_('select.booleanlist', 'showItem', '', $row->showItem);

		// published
		$lists['isFeatured'] = JHTML::_('select.booleanlist', 'isFeatured', '', $row->isFeatured);

		require_once JPATH_COMPONENT . '/views/items.php';
		FLItemsViewItem::batchAdd($row, $lists);
	}

	function batchSave()
	{
		global $mainframe;
		
		require_once(JPATH_COMPONENT.'/helpers/resize.php');

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_fl_items' );
		// Initialize variables
		$db =& JFactory::getDBO();
		jimport('joomla.filesystem.file');

		$post	= JRequest::get( 'post' );
		
		if(empty($post['ordering'])) {
			$query = "SELECT MAX(ordering)+1 FROM #__fl_items";
			$db->setQuery($query);
			$ordering = $db->loadResult();
		}
		
		$row =& JTable::getInstance('items', 'FLItemsTable');
		
		$numToId = array();
		
		// Create Items
		foreach($post as $key => $val) {
			if(strpos($key, "name-") !== false && $val) {
				$split = explode("-", $key);
				$num = $split[1];
				
				// ALIAS CHECK
				$newAlias = trim(preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%-]/s', '', strtolower($val)));
				$newAlias = str_replace(" ", "-", $newAlias);
				$newAlias = str_replace("--", "-", $newAlias);
				
				$query = "SELECT COUNT(*) FROM #__fl_items WHERE alias = ".$db->quote($newAlias);
				$db->setQuery($query);
				$aliasCount = $db->loadResult();
				if($aliasCount) {
					// Add numbers until we find a good one.
					$found = false;
					$i = 2;
					while(!$found) {
						$checkAlias = $newAlias . "-$i";
						$query = "SELECT COUNT(*) FROM #__fl_items WHERE alias = ".$db->quote($checkAlias);
						$db->setQuery($query);
						$aliasCount = $db->loadResult();
						if($aliasCount > 0) {
							$i++;
						} else {
							$newAlias = $checkAlias;
							$found = true;
						}
						if($i > 1000) {
							echo "Bad Alias.";
							exit;
						}
					}
				}
				// End Alias Check
				
				$row->item_id = 0;
				$row->isFeatured = 0;
				$row->showItem = 1;
				$row->name = $val;
				$row->alias = $newAlias;
				$row->item_category_id = $post['item_category_id'];
				$row->ordering = $ordering;
				$row->store();
				
				$numToId[$num] = $row->item_id;
				$ordering++;
			}
		}
		 
		// Build Properties
		foreach($post as $key => $val) {
		    $val = JRequest::getVar( $key, '', 'post', 'string', JREQUEST_ALLOWHTML ); 
            
			if(strpos($key, "val-") !== false) {
				$propData = substr($key, 4);
				$split = explode("-", $propData);
				$prop = $split[0];
				$num = $split[1];
				$propItemId = $numToId[$num];
				if(!$propItemId) {
					continue;
				}
				
				$query = 'SELECT items_item_property_id '
				. ' FROM #__fl_items_item_property AS pi'
				. ' WHERE item_id = ' . $row->item_id
				. ' AND item_property_id = (SELECT item_property_id FROM #__fl_items_properties WHERE  name = "' . $prop . '" AND item_category_id = ' . $row->item_category_id . ')'
				;
				$db->setQuery( $query );
				$isPropUpdate = $db->loadResult();
				
				$query = 'SELECT item_property_id FROM #__fl_items_properties WHERE name = "' . $prop . '" AND item_category_id = ' . $row->item_category_id;
				$db->setQuery( $query );
				$propId = $db->loadResult();
				
				$propRow =& JTable::getInstance('itemproperty', 'FLItemsTable');
				
				$propRow->item_id = $propItemId;
				$propRow->item_property_id = $propId;
				$propRow->value = $val;
				$propRow->store();
			}
		}

		//// End Properties
		$task = JRequest::getCmd( 'task' );
		switch ($task)
		{
			case 'apply':
				$link = 'index.php?option=com_fl_items&c=item&task=edit&item_id[]='. $row->item_id ;
				break;

			case 'save':
			default:
				$link = 'index.php?option=com_fl_items&c=item&item_category_id='. $row->item_category_id ;
				break;
		}
		$this->setRedirect( $link, JText::_( 'Items Saved' ) );
	}

	/**
	 * Upload From CSV
	*/
	function uploadCSV() {
		$db = &JFactory::getDBO();
		$post = JRequest::get('post');
		$category = $post['item_category_id'];
		$thisTempFolderPath = JPATH_COMPONENT . '/tmp/';

		$thisUpload = JRequest::getVar('upload', null, 'files', 'array');
		if (isset($thisUpload)) {
			jimport('joomla.filesystem.folder');
			jimport('joomla.filesystem.file');

			if (!JFolder::exists($thisTempFolderPath)) {
				JFolder::create($thisTempFolderPath);
			}

			JFile::upload($thisUpload['tmp_name'], $thisTempFolderPath . '/tmp.csv');

			require_once(JPATH_COMPONENT . '/helpers/SimpleExcel/SimpleExcel.php');
			require_once(JPATH_COMPONENT . '/helpers/SimpleExcel/Parser/IParser.php');
			require_once(JPATH_COMPONENT . '/helpers/SimpleExcel/Parser/BaseParser.php');
			require_once(JPATH_COMPONENT . '/helpers/SimpleExcel/Parser/CSVParser.php');
			require_once(JPATH_COMPONENT . '/helpers/SimpleExcel/Writer/IWriter.php');
			require_once(JPATH_COMPONENT . '/helpers/SimpleExcel/Writer/BaseWriter.php');
			require_once(JPATH_COMPONENT . '/helpers/SimpleExcel/Writer/CSVWriter.php');
			require_once(JPATH_COMPONENT . '/helpers/SimpleExcel/Exception/SimpleExcelException.php');

			// Get CSV Titles
			$excel = new SimpleExcel('csv');
			$excel->parser->loadFile($thisTempFolderPath.'tmp.csv');
			$csvCols = $excel->parser->getRow(1);

			// Get properties
			$query = 'SELECT p.item_property_id, p.caption '
			. ' FROM #__fl_items_properties AS p'
			. ' WHERE p.enableProperty = 1'
			. ' AND p.item_category_id = ' . $category
				. ' ORDER BY p.ordering'
			;

			$db->setQuery($query);
			$props = $db->loadObjectList();

			array_unshift($props, array('item_property_id' => 'parent_item_id', 'caption' => 'Parent Item ID'));
			array_unshift($props, array('item_property_id' => 'showItem', 'caption' => 'Publish Item (1/0)'));
			array_unshift($props, array('item_property_id' => 'ordering', 'caption' => 'Item Ordering'));
			array_unshift($props, array('item_property_id' => 'isFeatured', 'caption' => 'Featured (1/0)'));
			// array_unshift($props, array('item_property_id' => 'alias', 'caption' => 'Item Alias'));
			array_unshift($props, array('item_property_id' => 'name', 'caption' => 'Item Name'));
			array_unshift($props, array('item_property_id' => 0, 'caption' => '---- SKIP ----'));

			$propSelect = JHTML::_('select.genericlist', $props, 'col-###', 'style="width: 300px;"', 'item_property_id', 'caption');

			require_once JPATH_COMPONENT . '/views/items.php';
			FLItemsViewItem::uploadMap($csvCols, $propSelect, $category);
		} else if($post['mapped'] == 1) {
			$mapping = array();
			foreach($post as $k => $v) {
				if(!$v) {
					continue;
				}
				if(strpos($k, "col-") !== false) {
					$split = explode("-", $k);
					$thisCol = $split[1] + 1;
					if($v == "name") {
						$nameCol = $thisCol;
					} else if($v == "isFeatured") {
						$featuredCol = $thisCol;
					} else if($v == "ordering") {
						$orderingCol = $thisCol;
					} else if($v == "showItem") {
						$showCol = $thisCol;
					} else if($v == "parent_item_id") {
						$parentCol = $thisCol;
					} else {
						$mapping[$thisCol] = $v;
					}
				}
			}

			require_once(JPATH_COMPONENT . '/helpers/SimpleExcel/SimpleExcel.php');
			require_once(JPATH_COMPONENT . '/helpers/SimpleExcel/Parser/IParser.php');
			require_once(JPATH_COMPONENT . '/helpers/SimpleExcel/Parser/BaseParser.php');
			require_once(JPATH_COMPONENT . '/helpers/SimpleExcel/Parser/CSVParser.php');
			require_once(JPATH_COMPONENT . '/helpers/SimpleExcel/Writer/IWriter.php');
			require_once(JPATH_COMPONENT . '/helpers/SimpleExcel/Writer/BaseWriter.php');
			require_once(JPATH_COMPONENT . '/helpers/SimpleExcel/Writer/CSVWriter.php');
			require_once(JPATH_COMPONENT . '/helpers/SimpleExcel/Exception/SimpleExcelException.php');

			// Get CSV Titles
			$excel = new SimpleExcel('csv');
			$parser = $excel->parser;
			$parser->loadFile($thisTempFolderPath.'tmp.csv');

			$row = 2;

			$propsToAdd = array();

			$query = "SELECT MAX(ordering)+1 FROM #__fl_items";
			$db->setQuery($query);
			$newOrdering = $db->loadResult();

			while($parser->isRowExists($row)) {
				// Check for name field
				$name = $parser->getCell($row, $nameCol);
				$alias = "";
				if($featuredCol) {
					$featured = $parser->getCell($row, $featuredCol);
				}
				if($orderingCol) {
					$ordering = $parser->getCell($row, $orderingCol);
				}
				if($showCol) {
					$show = $parser->getCell($row, $showCol);
				}
				if($parentCol) {
					$parent = $parser->getCell($row, $parentCol);
				}
				if($name) {
					// Add Item
					$newItem = &JTable::getInstance('items', 'FLItemsTable');
					$newItem->item_category_id = $post['item_category_id'];
					$newItem->name = $name;
					
					// ALIAS CHECK
					if(empty($alias)) {
						$alias = $name;
					}
					$newAlias = trim(preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%-]/s', '', strtolower($alias)));
					$newAlias = str_replace(" ", "-", $newAlias);
					$newAlias = str_replace("--", "-", $newAlias);
					$alias = $newAlias;
					
					$thisItemId = $post['item_id'];
					$query = "SELECT COUNT(*) FROM #__fl_items WHERE alias = ".$db->quote($alias);
					if($thisItemId) {
						$query .= " AND item_id != ".$db->quote($thisItemId);
					}
					$db->setQuery($query);
					$aliasCount = $db->loadResult();
					if($aliasCount) {
						// Add numbers until we find a good one.
						$found = false;
						$i = 2;
						while(!$found) {
							$checkAlias = $alias . "-$i";
							$query = "SELECT COUNT(*) FROM #__fl_items WHERE alias = ".$db->quote($checkAlias);
							if($thisItemId) {
								$query .= " AND item_id != ".$db->quote($thisItemId);
							}
							$db->setQuery($query);
							$aliasCount = $db->loadResult();
							if($aliasCount > 0) {
								$i++;
							} else {
								$alias = $checkAlias;
								$found = true;
							}
							if($i > 1000) {
								echo "Bad Alias.";
								exit;
							}
						}
					}
					// End Alias Check
					
					$newItem->alias = $alias;
					if(is_numeric($featured)) {
						$newItem->isFeatured = $featured;
					}
					if($ordering && is_numeric($ordering)) {
						$newItem->ordering = $ordering;
					} else {
						$newItem->ordering = $newOrdering;
					}
					if(is_numeric($show)) {
						$newItem->showItem = $show;
					}
					if($parent && is_numeric($parent)) {
						$newItem->parent_item_id = $parent;
					}
					if (!$newItem->store()) {
						return JError::raiseWarning($newItem->getError());
					}

					// Add properties
					foreach($mapping as $propCol => $propId) {
						$newProp = &JTable::getInstance('Itemproperty', 'FLItemsTable');
						$newProp->item_id = $newItem->item_id;
						$newProp->item_property_id = $propId;
						$newProp->value = $parser->getCell($row, $propCol);
						echo "<br>$propId - $propCol = ". $parser->getCell($row, $propCol);
						if (!$newProp->store()) {
							return JError::raiseWarning($newProp->getError());
						}
					}
					exit;
				}

				$newOrdering++;
				$row++;
			}

			print "Import Success!";

		} else {
			require_once JPATH_COMPONENT . '/views/items.php';
			FLItemsViewItem::uploadSelect($category);
		}
	}

	/**
	 * Export CSV
	*/
	function exportCSV() {
		$db = &JFactory::getDBO();
		$post = JRequest::get('post');
		$category = $post['item_category_id'];
		
		$query = "
			SELECT SQL_CALC_FOUND_ROWS
				i.*, i.item_id AS slug,
				c.`name` AS category,
				c.`menuId` AS menuId,
				(SELECT filename FROM #__fl_items_image WHERE item_id = i.item_id ORDER BY ordering ASC LIMIT 1) as image
			FROM
				#__fl_items i
			INNER JOIN #__fl_items_category c ON c.item_category_id = i.item_category_id
			WHERE
				i.item_category_id = $category
			ORDER BY ordering
		";
		
		$db->setQuery( $query );
		$getItems = $db->loadAssocList();
		
		$getAll = array();
		
		$idList = "0";
		foreach($getItems as $item) {
			if($item['item_id']) {
				$idList .= ",".$item['item_id'];
				$getAll[$item['item_id']]['item'] = $item;
			}
		}
		
		// Get item properties
		$query = "
			SELECT ip.*, p.`name`, p.caption, p.type, (SELECT io.option FROM #__fl_items_option io WHERE io.item_property_multi_id = ip.value) AS selectValue
			FROM #__fl_items_item_property ip
			INNER JOIN #__fl_items_properties p ON p.item_property_id = ip.item_property_id
			WHERE 1 = (SELECT COUNT(*) FROM #__fl_items i WHERE i.showItem = 1 AND i.item_id = ip.item_id)
				AND ip.item_id IN ($idList)
		";
		$db->setQuery( $query);
		$getProps = $db->loadObjectList();
		
		$rows = array();
		$cols = array("name", "alias", "isFeatured", "ordering", "showItem", "parent_item_id");
		foreach($getProps as $prop) {
			if(!in_array($prop->name, $cols)) {
				$cols[] = $prop->name;
			}
			$getAll[$prop->item_id]['property'][$prop->name] = $prop;
		}
		
		$rows[] = $cols;
		
		foreach($getAll as $i) {
			$row = array();
			foreach($cols as $c) {
				if($i['item'][$c] || strlen($i['item'][$c])) {
					$row[] = $i['item'][$c];
				} else if($i['property'][$c]) {
					$row[] = $i['property'][$c]->value;
				} else {
					$row[] = "";
				}
			}
			$rows[] = $row;
		}
		
		require_once(JPATH_COMPONENT . '/helpers/SimpleExcel/SimpleExcel.php');
		require_once(JPATH_COMPONENT . '/helpers/SimpleExcel/Parser/IParser.php');
		require_once(JPATH_COMPONENT . '/helpers/SimpleExcel/Parser/BaseParser.php');
		require_once(JPATH_COMPONENT . '/helpers/SimpleExcel/Parser/CSVParser.php');
		require_once(JPATH_COMPONENT . '/helpers/SimpleExcel/Writer/IWriter.php');
		require_once(JPATH_COMPONENT . '/helpers/SimpleExcel/Writer/BaseWriter.php');
		require_once(JPATH_COMPONENT . '/helpers/SimpleExcel/Writer/CSVWriter.php');
		require_once(JPATH_COMPONENT . '/helpers/SimpleExcel/Exception/SimpleExcelException.php');
		
		$excel = new SimpleExcel('csv');
		
		$excel->writer->setData(
		    $rows
		);                       
		
		$excel->writer->setDelimiter(","); 
		$excel->writer->saveFile("fl-item-export-$category");
		
	}

	/**
	 * Save method
	 */
	function save() {
		global $mainframe;

		require_once JPATH_COMPONENT . '/helpers/resize.php';

		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		$this->setRedirect('index.php?option=com_fl_items');
		// Initialize variables
		$db = &JFactory::getDBO();
		jimport('joomla.filesystem.file');

		$post = JRequest::get('post');
		$cat = $post['item_category_id'];
		
		// ALIAS CHECK
		if(empty($post['alias'])) {
			$post['alias'] = $post['name'];
		}
		$newAlias = trim(preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%-]/s', '', strtolower($post['alias'])));
		$newAlias = str_replace(" ", "-", $newAlias);
		$newAlias = str_replace("--", "-", $newAlias);
		$post['alias'] = $newAlias;
		
		$thisItemId = $post['item_id'];
		$query = "SELECT COUNT(*) FROM #__fl_items WHERE alias = ".$db->quote($post['alias']);
		if($thisItemId) {
			$query .= " AND item_id != ".$db->quote($thisItemId);
		}
		$db->setQuery($query);
		$aliasCount = $db->loadResult();
		if($aliasCount) {
			// Add numbers until we find a good one.
			$found = false;
			$i = 2;
			while(!$found) {
				$checkAlias = $post['alias'] . "-$i";
				$query = "SELECT COUNT(*) FROM #__fl_items WHERE alias = ".$db->quote($checkAlias);
				if($thisItemId) {
					$query .= " AND item_id != ".$db->quote($thisItemId);
				}
				$db->setQuery($query);
				$aliasCount = $db->loadResult();
				if($aliasCount > 0) {
					$i++;
				} else {
					$post['alias'] = $checkAlias;
					$found = true;
				}
				if($i > 1000) {
					echo "Bad Alias.";
					exit;
				}
			}
		}
		// End Alias Check
		
		$query = "SELECT * FROM #__fl_items_category WHERE item_category_id = $cat";
		$db->setQuery($query);
		$categoryData = $db->loadObject();

		if (empty($post['ordering'])) {
			$isNewFirst = $categoryData->isNewFirst;

			if ($isNewFirst) {
				$query = "UPDATE #__fl_items SET ordering = ordering + 1 WHERE item_category_id = $cat";
				$db->setQuery($query);
				$db->execute();
				$post['ordering'] = 1;
			} else {
				$query = "SELECT MAX(ordering)+1 FROM #__fl_items";
				$db->setQuery($query);
				$newOrdering = $db->loadResult();
				$post['ordering'] = $newOrdering;
			}
		}

		$post['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWHTML);

		$row = &JTable::getInstance('items', 'FLItemsTable');

		$imageBasePath = JPATH_ROOT . '/images/fl_items/';
		if($categoryData->imageWidth && $categoryData->imageHeight) {
			$resizeImageWidth = $categoryData->imageWidth;
			$resizeImageHeight = $categoryData->imageHeight;
		} else {
			$resizeImageWidth = 480;
			$resizeImageHeight = 480;
		}

		if (!$row->bind($post)) {
			return JError::raiseWarning(500, $row->getError());
		}

		if (!$row->check()) {
			return JError::raiseWarning(500, $row->getError());
		}

		if ($row->item_id) {
			$isUpdate = 1;
		} else {
			$isUpdate = 0;
		}
		$row->imageFilename = $this_filename;
		if (!$row->store()) {
			return JError::raiseWarning($row->getError());
		}

		$row->checkin();

		jimport('joomla.filesystem.folder');

		$allowedFileTypes = array('image/png', 'image/jpeg', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
		$thisFolderPath = JPATH_ROOT . '/images/fl_items/' . $row->item_id;
		$thisOriginalFolderPath = $thisFolderPath . '/original';
		$thisFilePath = JPATH_ROOT . '/images/fl_items/files/' . $row->item_id;
		$thisOriginalFilePath = $thisFilePath . '/original';
		if (!JFolder::exists($thisFolderPath)) {
			JFolder::create($thisFolderPath);
		}
		if (!JFolder::exists($thisOriginalFolderPath)) {
			JFolder::create($thisOriginalFolderPath);
		}
		if (!JFolder::exists($thisFilePath)) {
			JFolder::create($thisFilePath);
		}
		if (!JFolder::exists($thisOriginalFilePath)) {
			JFolder::create($thisOriginalFilePath);
		}

		$imagetable = &JTable::getInstance('images', 'FLItemsTable');
		$image_resizer = new resizeImage();

		// Image Reordering
		$orderingIds = $post['img-ordering'];
		$split = explode(",", $orderingIds);
		$currentOrdering = 1;
		foreach($split as $id) {
			if(is_numeric($id)) {
				$imagetable->load( $id );
				$imagetable->ordering = $currentOrdering;
				$imagetable->store();
				$currentOrdering++;
			}
		}
		
		// Build Properties
		foreach ($post as $key => $val) {
			$val = JRequest::getVar($key, '', 'post', 'string', JREQUEST_ALLOWHTML);

			if (strpos($key, "val-sub-") !== false) {
				// Handle sub-item save.
				// Create/update sub-item.
				// Add sub-item's ID to base-item property value.
				$split = explode("-", substr($key, 8));
				$subItemParentId = $split[0];
				$subItemProp = $split[1];
				$subItemCategoryId = $split[2];
				$subItemValue = $val;
				$thisItemId = $row->item_id;

				$query = 'SELECT item_property_id FROM #__fl_items_properties WHERE name = "' . $subItemProp . '" AND item_category_id = ' . $subItemCategoryId;
				$db->setQuery($query);
				$propId = $db->loadResult();

				$query = 'SELECT sub_item_id FROM #__fl_items_item_sub_item WHERE item_id = "' . $thisItemId . '" AND sub_item_parent_id = ' . $subItemParentId;
				$db->setQuery($query);
				$subItemId = $db->loadResult();

				if (!$subItemId) {
					// Create new item
					$newRow = &JTable::getInstance('items', 'FLItemsTable');
					$newRow->name = "Sub-Item : $thisItemId : $subItemParentId";
					$newRow->parent_item_id = $thisItemId;
					$newRow->item_category_id = $subItemCategoryId;
					$newRow->store();
					$newRow->checkin();

					$subItemId = $newRow->item_id;

					// Create item-subitem connection
					$query = "INSERT INTO #__fl_items_item_sub_item (item_id, sub_item_id, sub_item_parent_id)
					VALUES ($thisItemId, $subItemId, $subItemParentId)";
					$db->setQuery($query);
					$db->execute();
				}

				$query = 'SELECT items_item_property_id '
					. ' FROM #__fl_items_item_property AS pi'
					. ' WHERE item_id = ' . $subItemId
					. ' AND item_property_id = ' . $propId
				;
				$db->setQuery($query);
				$isPropUpdate = $db->loadResult();

				$propRow = &JTable::getInstance('itemproperty', 'FLItemsTable');

				if ($isPropUpdate) {
					$propRow->load((int) $isPropUpdate);
					$propRow->value = $subItemValue;
					$propRow->store();
				} else {
					$propRow->item_id = $subItemId;
					$propRow->item_property_id = $propId;
					$propRow->value = $subItemValue;
					$propRow->store();
				}
			} else if (strpos($key, "val-") !== false || strpos($key, "mlink-") !== false) {
				$split = explode("-", $key,2);
				$prop = $split[1];

				$query = 'SELECT item_property_id, type FROM #__fl_items_properties WHERE  name = "' . $prop . '" AND item_category_id = ' . $row->item_category_id;
				$db->setQuery($query);
				$propDetails = $db->loadObject();
				$propertyType = $propDetails->type;
				
				if(strpos($propertyType, "-") !== false) {
					$split = explode("-", $propertyType, 2);
					$type = $split[0];
					$linkId = $split[1];
				} else {
					$type = $propertyType;
				}
				$type = ucwords(strtolower($type));
				
				if(file_exists(JPATH_ADMINISTRATOR.'/components/com_fl_items/helpers/fields/fliField'.$type.'.php')) {
					include_once(JPATH_ADMINISTRATOR.'/components/com_fl_items/helpers/fields/fliField'.$type.'.php');
					$className = "FliField".$type;
					$thisField = new $className($data);
				} else if(file_exists(JPATH_ADMINISTRATOR.'/components/com_fl_items/helpers/customFields/fliField'.$type.'.php')) {
					include_once(JPATH_ADMINISTRATOR.'/components/com_fl_items/helpers/customFields/fliField'.$type.'.php');
					$className = "FliField$type";
					$thisField = new $className($data);
				} else {
					echo "<div>Error: Custom class file required for type: " . $type . "</div>";
					exit;
				}
				
				// Custom prep values for save
				$val = $thisField->prepValueForSave($val);
				
				$query = 'SELECT items_item_property_id '
				. ' FROM #__fl_items_item_property AS pi'
				. ' WHERE item_id = ' . $row->item_id
				. ' AND item_property_id = ' . $propDetails->item_property_id
				;
				$db->setQuery($query);
				$isPropUpdate = $db->loadResult();

				$query = 'SELECT item_property_id FROM #__fl_items_properties WHERE name = "' . $prop . '" AND item_category_id = ' . $row->item_category_id;
				$db->setQuery($query);
				$propId = $db->loadResult();

				$propRow = &JTable::getInstance('itemproperty', 'FLItemsTable');

				if ($isPropUpdate) {
					$propRow->load((int) $isPropUpdate);
					$propRow->value = $val;
					$propRow->store();
				} else {
					$propRow->item_id = $row->item_id;
					$propRow->item_property_id = $propId;
					$propRow->value = $val;
					$propRow->store();
				}
			}
		}

		$files = JRequest::get('files');
		foreach ($files as $key => $val) {
			if (strpos($key, "file-") !== false) {
				// Check if it's a SUB-ITEM file
				if (strpos($key, "file-sub-") !== false) {
					// Handle sub-item save.
					$split = explode("-", substr($key, 9));
					$subItemParentId = $split[0];
					$subItemProp = $split[1];
					$subItemCategoryId = $split[2];
					$subItemValue = $val;
					$thisItemId = $row->item_id;

					$query = 'SELECT item_property_id FROM #__fl_items_properties WHERE name = "' . $subItemProp . '" AND item_category_id = ' . $subItemCategoryId;
					$db->setQuery($query);
					$propId = $db->loadResult();

					$query = 'SELECT sub_item_id FROM #__fl_items_item_sub_item WHERE item_id = "' . $thisItemId . '" AND sub_item_parent_id = ' . $subItemParentId;
					$db->setQuery($query);
					$subItemId = $db->loadResult();

					$this_imageUpload = JRequest::getVar($key, null, 'files', 'array');
					if (isset($this_imageUpload) && in_array($this_imageUpload['type'], $allowedFileTypes)) {
						$query = 'SELECT items_item_property_id, `value` '
							. ' FROM #__fl_items_item_property AS pi'
							. ' WHERE item_id = ' . $subItemId
							. ' AND item_property_id = ' . $propId
						;
						$db->setQuery($query);
						$res = $db->loadObjectList();
						$isPropUpdate = $res[0]->items_item_property_id;
						$oldFilename = $res[0]->value;

						$query = 'SELECT item_property_id FROM #__fl_items_properties WHERE name = "' . $subItemProp . '" AND item_category_id = ' . $subItemCategoryId;
						$db->setQuery($query);
						$propId = $db->loadResult();

						$propRow = &JTable::getInstance('itemproperty', 'FLItemsTable');

						$this_filename = $subItemId . '_' . JFilterOutput::stringURLSafe(JFile::stripExt($this_imageUpload['name'])) . '.' . JFile::getExt($this_imageUpload['name']);

						$thisSubFolderPath = JPATH_ROOT . '/images/fl_items/' . $subItemId;
						$thisSubOriginalFolderPath = $thisSubFolderPath . '/original';
						$thisSubFilePath = JPATH_ROOT . '/images/fl_items/files/' . $subItemId;
						$thisSubOriginalFilePath = $thisSubFilePath . '/original';
						if (!JFolder::exists($thisSubFolderPath)) {
							JFolder::create($thisSubFolderPath);
						}
						if (!JFolder::exists($thisSubOriginalFolderPath)) {
							JFolder::create($thisSubOriginalFolderPath);
						}
						if (!JFolder::exists($thisSubFilePath)) {
							JFolder::create($thisSubFilePath);
						}
						if (!JFolder::exists($thisSubOriginalFilePath)) {
							JFolder::create($thisSubOriginalFilePath);
						}

						if ($isPropUpdate) {
							JFile::delete($thisSubFilePath . '/' . $oldFilename);
							JFile::delete($thisSubOriginalFilePath . '/' . $oldFilename);
							$propRow->load((int) $isPropUpdate);
							$propRow->value = $this_filename;
							$propRow->store();
						} else {
							$propRow->item_id = $subItemId;
							$propRow->item_property_id = $propId;
							$propRow->value = $this_filename;
							$propRow->store();
						}

						JFile::upload($this_imageUpload['tmp_name'], $thisSubOriginalFilePath . '/' . $this_filename);
						$image_resizer->resize($thisSubOriginalFilePath . '/' . $this_filename, $thisSubFilePath . '/' . $this_filename, $resizeImageWidth, $resizeImageHeight, 0);
					}

					$imagetable->ordering = JRequest::getVar('ordering_' . $id);
					$imagetable->showImage = JRequest::getVar('showImage_' . $id);

					if (!$imagetable->store()) {
						return JError::raiseWarning($imagetable->getError());
					}

				} else {
					// Not a sub-item

					$prop = substr($key, 5);

					$this_imageUpload = JRequest::getVar($key, null, 'files', 'array');

					if (isset($this_imageUpload) && (in_array($this_imageUpload['type'], $allowedFileTypes) || in_array(JFile::getExt($this_imageUpload['name']), array("pdf")))) {

						$query = 'SELECT items_item_property_id, `value` '
						. ' FROM #__fl_items_item_property AS pi'
						. ' WHERE item_id = ' . $row->item_id
						. ' AND item_property_id = (SELECT item_property_id FROM #__fl_items_properties WHERE  name = "' . $prop . '" AND item_category_id = ' . $row->item_category_id . ')'
						;
						$db->setQuery($query);
						$res = $db->loadObjectList();
						$isPropUpdate = $res[0]->items_item_property_id;
						$oldFilename = $res[0]->value;

						$query = 'SELECT item_property_id FROM #__fl_items_properties WHERE name = "' . $prop . '" AND item_category_id = ' . $row->item_category_id;
						$db->setQuery($query);
						$propId = $db->loadResult();

						$propRow = &JTable::getInstance('itemproperty', 'FLItemsTable');

						$this_filename = $propId . '_' . JFilterOutput::stringURLSafe(JFile::stripExt($this_imageUpload['name'])) . '.' . JFile::getExt($this_imageUpload['name']);

						if ($isPropUpdate) {
							JFile::delete($thisFilePath . '/' . $oldFilename);
							JFile::delete($thisOriginalFilePath . '/' . $oldFilename);
							$propRow->load((int) $isPropUpdate);
							$propRow->value = $this_filename;
							$propRow->store();
						} else {
							$propRow->item_id = $row->item_id;
							$propRow->item_property_id = $propId;
							$propRow->value = $this_filename;
							$propRow->store();
						}

						JFile::upload($this_imageUpload['tmp_name'], $thisOriginalFilePath . '/' . $this_filename);
						$image_resizer->resize($thisOriginalFilePath . '/' . $this_filename, $thisFilePath . '/' . $this_filename, $resizeImageWidth, $resizeImageHeight, 0);
					}

					// $imagetable->ordering = JRequest::getVar('ordering_' . $id);
					// $imagetable->showImage = JRequest::getVar('showImage_' . $id);
// 
					// if (!$imagetable->store()) {
						// return JError::raiseWarning($imagetable->getError());
					// }
				}
			}
		}

		//// End Properties

		if ($isUpdate) {
			$query = 'SELECT pi.* '
			. ' FROM #__fl_items_image AS pi'
			. ' WHERE item_id = ' . $row->item_id
			;
			$db->setQuery($query);
			$imagelist = $db->loadObjectList();

			for ($iImage = 0, $nImage = count($imagelist); $iImage < $nImage; $iImage++) {
				$imagelistrow = &$imagelist[$iImage];
				$id = $imagelistrow->item_image_id;

				if (JRequest::getVar('delete_gallery_image_' . $id)) {
					$query = 'DELETE FROM #__fl_items_image'
						. ' WHERE item_image_id = ' . $id
					;
					$db->setQuery($query);
					if (!$db->query()) {
						JError::raiseWarning(500, $db->getError());
					}
					JFile::delete($thisFolderPath . '/' . $imagelistrow->filename);
					JFile::delete($thisOriginalFolderPath . '/' . $imagelistrow->filename);
				} else if ($imagetable->load((int) $id)) {
					$this_imageUpload = JRequest::getVar('filename_' . $id, null, 'files', 'array');
					if (isset($this_imageUpload) && in_array($this_imageUpload['type'], $allowedFileTypes)) {
						JFile::delete($thisFolderPath . '/' . $imagelistrow->filename);
						JFile::delete($thisOriginalFolderPath . '/' . $imagelistrow->filename);
						$this_filename = $id . '_' . JFilterOutput::stringURLSafe(JFile::stripExt($this_imageUpload['name'])) . '.' . JFile::getExt($this_imageUpload['name']);
						JFile::upload($this_imageUpload['tmp_name'], $thisOriginalFolderPath . '/' . $this_filename);

						$image_resizer->resize($thisOriginalFolderPath . '/' . $this_filename, $thisFolderPath . '/' . $this_filename, $resizeImageWidth, $resizeImageHeight, 0);

						$imagetable->filename = $this_filename;

					}

					$imagetable->ordering = JRequest::getVar('ordering_' . $id);
					$imagetable->caption = JRequest::getVar('caption_' . $id);
					$imagetable->showImage = JRequest::getVar('showImage_' . $id);

					if (!$imagetable->store()) {
						return JError::raiseWarning($imagetable->getError());
					}
				} else {
					return JError::raiseWarning(500, $imagetable->getError());
				}
			}
		}

		$query = "SELECT MAX(ordering)+1 FROM #__fl_items_image WHERE item_id = " . $row->item_id;
		$db->setQuery($query);
		$newOrdering = $db->loadResult();
		if(!$newOrdering) {
			$newOrdering = 1;
		}

		$this_imageZipUpload = JRequest::getVar('uploadZipFile', null, 'files', 'array');
		if (isset($this_imageZipUpload) && strpos($this_imageZipUpload['type'], "zip")) {
			$thisTempFolderPath = JPATH_ROOT . '/images/fl_items/temp';
			if (!JFolder::exists($thisTempFolderPath)) {
				JFolder::create($thisTempFolderPath);
			}
			

			JFile::upload($this_imageZipUpload['tmp_name'], $thisTempFolderPath . '/' . $this_imageZipUpload['name'], false, true);
			JArchive::extract($thisTempFolderPath . '/' . $this_imageZipUpload['name'], $thisTempFolderPath);

			$listOfImages = JFolder::files($thisTempFolderPath, '.', true, true);
			$allowedExtensions = array("JPG", "PNG", "JPEG");

			foreach ($listOfImages AS $thisImage) {
				if(strtoupper(JFile::getExt($thisImage)) == "ZIP") {
					continue;
				}
				if(!in_array(strtoupper(JFile::getExt($thisImage)), $allowedExtensions)) {
					continue;
				}
				$imagetable->item_image_id = 0;
				$imagetable->item_id = $row->item_id;
				$imagetable->store();
				$this_filename = $imagetable->item_image_id . '_' . JFilterOutput::stringURLSafe(JFile::stripExt(JFile::getName($thisImage))) . "." . JFile::getExt($thisImage);
				JFile::move($thisImage, $thisOriginalFolderPath . '/' . $this_filename);
				
				$image_resizer->resize($thisOriginalFolderPath . '/' . $this_filename, $thisFolderPath . '/' . $this_filename, $resizeImageWidth, $resizeImageHeight, 0);
				
				if($categoryData->imageWidth && $categoryData->imageHeight) {
					$image_resizer->resize($thisOriginalFolderPath . '/' . $this_filename, $thisOriginalFolderPath . '/' . $this_filename, $categoryData->imageWidth, $categoryData->imageHeight, 0);
				}
				
				$imagetable->filename = $this_filename;
				$imagetable->ordering = $newOrdering;
				$imagetable->store();
				
				// Add watermark?
				if($categoryData->addWatermark && $categoryData->watermarkImage && file_exists(JPATH_SITE."/images/fl_items/$categoryData->watermarkImage")) {
					$stamp = imagecreatefrompng(JPATH_SITE."/images/fl_items/$categoryData->watermarkImage");

					$isJpeg = false;
					if(strtoupper(JFile::getExt($thisImage)) == "JPG" || strtoupper(JFile::getExt($thisImage)) == "JPEG") {
						$isJpeg = true;
						$thisImg = imagecreatefromjpeg($thisOriginalFolderPath . '/' . $this_filename);
					} else {
						$thisImg = imagecreatefrompng($thisOriginalFolderPath . '/' . $this_filename);
					}
					
					$saveWatermarkLocation = $thisOriginalFolderPath . '/' . $this_filename;
					
					$margin = 10;
					
					if($categoryData->watermarkPosition == 0) { // Center
						$top = imagesy($thisImg)/2 - imagesy($stamp)/2;
						$left = imagesx($thisImg)/2 - imagesx($stamp)/2;
					} else if($categoryData->watermarkPosition == 1) { // Top Left
						$top = $margin;
						$left = $margin;
					} else if($categoryData->watermarkPosition == 2) { // Top Right
						$top = $margin;
						$left = imagesx($thisImg) - imagesx($stamp) - $margin;
					} else if($categoryData->watermarkPosition == 3) { // Bottom Left
						$top = imagesy($thisImg) - imagesy($stamp) - $margin;
						$left = $margin;
					} else if($categoryData->watermarkPosition == 4) { // Bottom Right
						$top = imagesy($thisImg) - imagesy($stamp) - $margin;
						$left = imagesx($thisImg) - imagesx($stamp) - $margin;
					}
					imagecopy(
						$thisImg,
						$stamp, 
						$left, 
						$top, 
						0, 
						0, 
						imagesx($stamp), 
						imagesy($stamp)
					);
					
					if($isJpeg) {
						imagejpeg($thisImg, $saveWatermarkLocation);
					} else {
						imagepng($thisImg, $saveWatermarkLocation);
					}
				}
				// End watermark

				$newOrdering++;
			}
			JFolder::delete($thisTempFolderPath);
		}

		for ($iNewImage = 1; $iNewImage <= 5; $iNewImage++) {
			$this_imageUpload = JRequest::getVar('new_filename_' . $iNewImage, null, 'files', 'array');
			if (isset($this_imageUpload) && in_array($this_imageUpload['type'], $allowedFileTypes) && !empty($this_imageUpload['tmp_name'])) {
				$imagetable->item_image_id = 0;
				$imagetable->item_id = $row->item_id;
				$imagetable->showImage = 1;
				$imagetable->store();
				$this_filename = $imagetable->item_image_id . '_' . JFilterOutput::stringURLSafe(JFile::stripExt($this_imageUpload['name'])) . '.' . JFile::getExt($this_imageUpload['name']);
				JFile::upload($this_imageUpload['tmp_name'], $thisOriginalFolderPath . '/tmp-' . $this_filename);
				$image_resizer->resize($thisOriginalFolderPath . '/tmp-' . $this_filename, $thisOriginalFolderPath . '/' . $this_filename, $resizeImageWidth, $resizeImageHeight, 0);
				$image_resizer->resize($thisOriginalFolderPath . '/' . $this_filename, $thisFolderPath . '/' . $this_filename, $resizeImageWidth, $resizeImageHeight, 0);
				
				unlink($thisOriginalFolderPath . '/tmp-' . $this_filename);


				$imagetable->filename = $this_filename;
				$imagetable->ordering = $newOrdering;
				$imagetable->store();

				$newOrdering++;
			}
		}
		$task = JRequest::getCmd('task');
		switch ($task) {
		case 'apply':
			$link = 'index.php?option=com_fl_items&c=item&task=edit&item_id[]=' . $row->item_id;
			break;

		case 'save':
		default:
			$link = 'index.php?option=com_fl_items&c=item&item_category_id=' . $row->item_category_id;
			break;
		}
		$this->setRedirect($link, JText::_('Item Saved'));
	}

	function cancel() {
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// Initialize variables
		$db = &JFactory::getDBO();
		$post = JRequest::get('post');
		$row = &JTable::getInstance('items', 'FLItemsTable');
		$row->bind($post);
		$row->checkin();

		$this->setRedirect('index.php?option=com_fl_items&c=item&item_category_id=' . $row->item_category_id);
	}

	function publish() {
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		$post = JRequest::get('post');
		$item_category_id = $post['item_category_id'];

		$this->setRedirect('index.php?option=com_fl_items&c=item&item_category_id=' . $item_category_id);

		// Initialize variables
		$db = &JFactory::getDBO();
		$user = &JFactory::getUser();
		$item_id = JRequest::getVar('item_id', array(), 'post', 'array');
		$task = JRequest::getCmd('task');
		$publish = ($task == 'publish');
		$n = count($item_id);

		if (empty($item_id)) {
			return JError::raiseWarning(500, JText::_('No items selected'));
		}

		JArrayHelper::toInteger($item_id);
		$item_ids = implode(',', $item_id);

		$query = 'UPDATE #__fl_items'
		. ' SET showItem = ' . (int) $publish
			. ' WHERE item_id IN ( ' . $item_ids . '  )'
		;
		$db->setQuery($query);
		if (!$db->query()) {
			return JError::raiseWarning(500, $db->getError());
		}
		$this->setMessage(JText::sprintf($publish ? 'Items published' : 'Items unpublished', $n));
	}

	/**
	 * Save the new order given by user
	 */
	function saveOrder() {
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		$this->setRedirect('index.php?option=com_fl_items');

		// Initialize variables
		$db = &JFactory::getDBO();
		$item_id = JRequest::getVar('item_id', array(), 'post', 'array');
		$order = JRequest::getVar('order', array(), 'post', 'array');
		$row = &JTable::getInstance('items', 'FLItemsTable');
		$total = count($item_id);
		$conditions = array();

		if (empty($item_id)) {
			return JError::raiseWarning(500, JText::_('No items selected'));
		}

		// update ordering values
		for ($i = 0; $i < $total; $i++) {
			$row->load((int) $item_id[$i]);
			if ($row->ordering != $order[$i]) {
				$row->ordering = $order[$i];
				if (!$row->store()) {
					return JError::raiseError(500, $db->getErrorMsg());
				}
				// remember to reorder this category
				/*
				$condition = 'catid = '.(int) $row->catid;
				$found = false;
				foreach ($conditions as $cond) {
					if ($cond[1] == $condition)
					{
						$found = true;
						break;
					}
				}
				if (!$found) {
					$conditions[] = array ( $row->bid, $condition );
				}
*/
			}
		}

/*
// execute reorder for each category
foreach ($conditions as $cond)
{
$row->load( $cond[0] );
$row->reorder( $cond[1] );
}
 */

		$this->setMessage(JText::_('New ordering saved'));
	}

	function remove() {
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		$post = JRequest::get('post');
		$item_category_id = $post['item_category_id'];

		$this->setRedirect('index.php?option=com_fl_items&c=item&item_category_id=' . $item_category_id);

		// Initialize variables
		$db = &JFactory::getDBO();
		$item_id = JRequest::getVar('item_id', array(), 'post', 'array');
		$n = count($item_id);
		JArrayHelper::toInteger($item_id);

		if ($n) {
			$query = 'DELETE FROM #__fl_items'
			. ' WHERE item_id = ' . implode(' OR item_id = ', $item_id)
			;
			$db->setQuery($query);
			if (!$db->query()) {
				JError::raiseWarning(500, $db->getError());
			}

			$query = 'DELETE FROM #__fl_items'
			. ' WHERE item_id = ' . implode(' OR item_id = ', $item_id)
			;
			$db->setQuery($query);

			if (!$db->query()) {
				JError::raiseWarning(500, $db->getError());
			}
		}

		$this->setMessage(JText::sprintf('Items removed', $n));
	}

	function ajaximg() {
		require_once JPATH_COMPONENT . '/helpers/resize.php';
		require_once(JPATH_COMPONENT . "/assets/tinify/lib/Tinify/Exception.php");
		require_once(JPATH_COMPONENT . "/assets/tinify/lib/Tinify/ResultMeta.php");
		require_once(JPATH_COMPONENT . "/assets/tinify/lib/Tinify/Result.php");
		require_once(JPATH_COMPONENT . "/assets/tinify/lib/Tinify/Source.php");
		require_once(JPATH_COMPONENT . "/assets/tinify/lib/Tinify/Client.php");
		require_once(JPATH_COMPONENT . "/assets/tinify/lib/Tinify.php");
		
		\Tinify\setKey("LlHJv6xt6VxMxRWLvkQvryqTcLkKM79l");
		
		jimport('joomla.filesystem.file');
		$db = &JFactory::getDBO();
		
		$prop = $_GET['property'];
		$id = $_GET['item_id'];
		$query = 'SELECT item_category_id '
				. ' FROM #__fl_items'
				. ' WHERE item_id = ' . $id;
		$db->setQuery($query);
		$item_category_id = $db->loadResult();
		
		$allowedFileTypes = array('image/png', 'image/jpeg');
		$thisFolderPath = JPATH_ROOT . '/images/fl_items/' . $id;
		$thisOriginalFolderPath = $thisFolderPath . '/original';
		$thisFilePath = JPATH_ROOT . '/images/fl_items/files/' . $id;
		$thisOriginalFilePath = $thisFilePath . '/original';
		if (!JFolder::exists($thisFolderPath)) {
			JFolder::create($thisFolderPath);
		}
		if (!JFolder::exists($thisOriginalFolderPath)) {
			JFolder::create($thisOriginalFolderPath);
		}
		if (!JFolder::exists($thisFilePath)) {
			JFolder::create($thisFilePath);
		}
		if (!JFolder::exists($thisOriginalFilePath)) {
			JFolder::create($thisOriginalFilePath);
		}
		
		$image_resizer = new resizeImage();
		
		$files = JRequest::get('files');
		foreach ($files as $key => $val) {
	
			$this_imageUpload = JRequest::getVar($key, null, 'files', 'array');
			
			if (isset($this_imageUpload) && (in_array($this_imageUpload['type'], $allowedFileTypes))) {
				if($prop != "new_filename_1") {
					$query = 'SELECT items_item_property_id, `value` '
						. ' FROM #__fl_items_item_property AS pi'
						. ' WHERE item_id = ' . $id
						. ' AND item_property_id = (SELECT item_property_id FROM #__fl_items_properties WHERE name = "' . $prop . '" AND item_category_id = ' . $item_category_id . ')'
						;
						
						$db->setQuery($query);
						$res = $db->loadObjectList();
						$isPropUpdate = $res[0]->items_item_property_id;
						$oldFilename = $res[0]->value;
			
						$query = 'SELECT item_property_id FROM #__fl_items_properties WHERE name = "' . $prop . '" AND item_category_id = ' . $item_category_id;
						$db->setQuery($query);
						$propId = $db->loadResult();
			
						$propRow = &JTable::getInstance('itemproperty', 'FLItemsTable');
			
						$this_filename = $propId . '_' . JFilterOutput::stringURLSafe(JFile::stripExt($this_imageUpload['name'])) . '.' . JFile::getExt($this_imageUpload['name']);
			
						if ($isPropUpdate) {
							JFile::delete($thisFilePath . '/' . $oldFilename);
							JFile::delete($thisOriginalFilePath . '/' . $oldFilename);
							$propRow->load((int) $isPropUpdate);
							$propRow->value = $this_filename;
							$propRow->store();
						} else {
							$propRow->item_id = $id;
							$propRow->item_property_id = $propId;
							$propRow->value = $this_filename;
							$propRow->store();
						}
			
						JFile::upload($this_imageUpload['tmp_name'], $thisOriginalFilePath . '/' . $this_filename);
						$image_resizer->resize($thisOriginalFilePath . '/' . $this_filename, $thisFilePath . '/' . $this_filename, $resizeImageWidth, $resizeImageHeight, 0);
				} else {
					$imagetable = &JTable::getInstance('images', 'FLItemsTable');
					
					$query = "SELECT MAX(ordering)+1 FROM #__fl_items_image WHERE item_id = " . $id;
					$db->setQuery($query);
					$newOrdering = $db->loadResult();
					if(!$newOrdering) {
						$newOrdering = 1;
					}
			
					$imagetable->item_image_id = 0;
					$imagetable->item_id = $id;
					$imagetable->showImage = 1;
					$imagetable->store();
					$this_filename_original = $imagetable->item_image_id . '_' . JFilterOutput::stringURLSafe(JFile::stripExt($this_imageUpload['name'])) . '_original.' . JFile::getExt($this_imageUpload['name']);
					$this_filename_compressed = $imagetable->item_image_id . '_' . JFilterOutput::stringURLSafe(JFile::stripExt($this_imageUpload['name'])) . '.' . JFile::getExt($this_imageUpload['name']);
					JFile::upload($this_imageUpload['tmp_name'], $thisOriginalFolderPath . '/' . $this_filename_original);
					
					$compress = true;
					$fileBytes = filesize($thisOriginalFolderPath . '/' . $this_filename_original);
					if($fileBytes) {
						$fileKB = $fileBytes / 1024;
						if($fileKB < 250) {
							$compress = false;
						}
					}
					if($compress) {
						try {
							$source = \Tinify\fromFile( $thisOriginalFolderPath . '/' . $this_filename_original );
							$source->toFile( $thisOriginalFolderPath . '/' . $this_filename_compressed );
							JFile::delete($thisOriginalFolderPath . '/' . $this_filename_original);
						} catch(\Tinify\AccountException $e) {
							// Probably over API limit
							JFile::move($thisOriginalFolderPath . '/' . $this_filename_original, $thisOriginalFolderPath . '/' . $this_filename_compressed);
						} catch(Exception $e) {
						    // Something else went wrong, unrelated to the Tinify API.
							JFile::move($thisOriginalFolderPath . '/' . $this_filename_original, $thisOriginalFolderPath . '/' . $this_filename_compressed);
						}
					} else {
						JFile::move($thisOriginalFolderPath . '/' . $this_filename_original, $thisOriginalFolderPath . '/' . $this_filename_compressed);
					}
	
					$image_resizer->resize($thisOriginalFolderPath . '/' . $this_filename_compressed, $thisFolderPath . '/' . $this_filename_compressed, $resizeImageWidth, $resizeImageHeight, 0);
	
					$imagetable->filename = $this_filename_compressed;
					$imagetable->ordering = $newOrdering;
					$imagetable->store();

					// Add watermark?
					$query = "SELECT item_category_id FROM #__fl_items WHERE item_id = " . $id;
					$db->setQuery($query);
					$categoryId = $db->loadResult();
					
					$query = "SELECT * FROM #__fl_items_category WHERE item_category_id = " . $categoryId;
					$db->setQuery($query);
					$categoryDetails = $db->loadObject();
					
					if($categoryDetails->addWatermark && $categoryDetails->watermarkImage && file_exists(JPATH_SITE."/images/fl_items/$categoryDetails->watermarkImage")) {
						$stamp = imagecreatefrompng(JPATH_SITE."/images/fl_items/$categoryDetails->watermarkImage");
						
						$isJpeg = false;
						if($this_imageUpload['type'] == "image/jpeg") {
							$isJpeg = true;
							$thisImg = imagecreatefromjpeg($thisOriginalFolderPath . '/' . $this_filename_compressed);
						} else {
							$thisImg = imagecreatefrompng($thisOriginalFolderPath . '/' . $this_filename_compressed);
						}
						
						$saveWatermarkLocation = $thisOriginalFolderPath . '/' . $this_filename_compressed;
						
						$margin = 10;
						
						if($categoryDetails->watermarkPosition == 0) { // Center
							$top = imagesy($thisImg)/2 - imagesy($stamp)/2;
							$left = imagesx($thisImg)/2 - imagesx($stamp)/2;
						} else if($categoryDetails->watermarkPosition == 1) { // Top Left
							$top = $margin;
							$left = $margin;
						} else if($categoryDetails->watermarkPosition == 2) { // Top Right
							$top = $margin;
							$left = imagesx($thisImg) - imagesx($stamp) - $margin;
						} else if($categoryDetails->watermarkPosition == 3) { // Bottom Left
							$top = imagesy($thisImg) - imagesy($stamp) - $margin;
							$left = $margin;
						} else if($categoryDetails->watermarkPosition == 4) { // Bottom Right
							$top = imagesy($thisImg) - imagesy($stamp) - $margin;
							$left = imagesx($thisImg) - imagesx($stamp) - $margin;
						}
						imagecopy(
							$thisImg,
							$stamp, 
							$left, 
							$top, 
							0, 
							0, 
							imagesx($stamp), 
							imagesy($stamp)
						);
						
						if($isJpeg) {
							imagejpeg($thisImg, $saveWatermarkLocation);
						} else {
							imagepng($thisImg, $saveWatermarkLocation);				
						}
					}
					// End watermark
					
					echo $imagetable->item_image_id;
					exit;
	
					$newOrdering++;
				}
			}
			echo "done";
			exit;
		}
		exit;
	}

	function saveOrderAjax() {
		$db =& JFactory::getDBO();
		
		// Get the input
		$items = $this->input->post->get('item_id', array(), 'array');
		JArrayHelper::toInteger($items);
		
		// Get orders
		$sql = "SELECT ordering FROM #__fl_items WHERE item_id IN (".implode(",", $items).") ORDER BY ordering";
		$db->setQuery($sql);
		$ordering = $db->loadColumn();
		
		$c = 0;
		foreach($items as $i) {
			$sql = "UPDATE #__fl_items SET ordering = ".$ordering[$c]." WHERE item_id = $i";
			$db->setQuery($sql);
			$db->execute();
			$c++;
		}
		
		echo "1";
		JFactory::getApplication()->close();
	}

	function updateDescription() {
		$post = JRequest::get('post');
		$newDescription = JRequest::getVar( 'description', '', 'post', 'string', JREQUEST_ALLOWHTML ); 
		$categoryId = $post['item_category_id'];

		$row =& JTable::getInstance('categories', 'FLItemsTable');
		$row->load($categoryId);

		$row->description = $newDescription;

		$row->store();

		$this->setRedirect('index.php?option=com_fl_items&c=item&item_category_id=' . $post['item_category_id']);
		$this->setMessage(JText::sprintf('Description has been updated!', $n));
	}
}
