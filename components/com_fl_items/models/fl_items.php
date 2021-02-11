<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class FLItemsModelfl_items extends JModelLegacy
{
	function getAllCount($category = 0, $searchTerms = array()) 
	{
		$db =& JFactory::getDBO();
		
		$searchWheres = array();
		$searchWhere = "";
		foreach($searchTerms as $st) {
			if(strpos($st, "_")) {
				$split = explode("_", $st, 2);
				$propertyName = $split[0];
				$propertyValue = $split[1];
				if($propertyName == "search"){
					$searchWheres[] = "(i.name LIKE '%$propertyValue%' OR (0 < (
								SELECT COUNT(*) 
								FROM #__fl_items_item_property iip
								INNER JOIN #__fl_items_properties ip ON ip.isSearchable = 1
								WHERE iip.item_id = i.item_id AND iip.item_property_id = ip.item_property_id AND iip.value LIKE '%" . $propertyValue . "%' )))";
				} else if ($propertyName == "parent") {
					$searchWheres[] = 'i.parent_item_id = (SELECT p.item_id FROM #__fl_items p WHERE p.name="'.$propertyValue.'")';
				} else {
					// check type
					$sql = "SELECT SQL_CALC_FOUND_ROWS type FROM #__fl_items_properties WHERE name = '" . $propertyName . "'";
					$db->setQuery( $sql );
					$searchType = $db->loadResult();
					if($searchType == "select") {
						$propertyValue = explode("|", $propertyValue);
						$searchWheres[] = "1 = (
							SELECT COUNT(*) 
							FROM #__fl_items_item_property iip
							INNER JOIN #__fl_items_properties ip ON ip.name = '" . $propertyName . "'
							INNER JOIN #__fl_items_option io ON io.item_property_id = ip.item_property_id AND io.option IN('" . implode("','",$propertyValue) . "')
							WHERE iip.item_id = i.item_id AND iip.item_property_id = ip.item_property_id AND iip.value = io.item_property_multi_id )";
					} else if($searchType == "multi") {
						$propertyValue = explode("|", $propertyValue);
						$searchWheres[] = "1 = (
							SELECT COUNT(*) 
							FROM #__fl_items_item_property iip
							INNER JOIN #__fl_items_properties ip ON ip.name = '" . $propertyName . "'
							INNER JOIN #__fl_items_option io ON io.item_property_id = ip.item_property_id AND io.option IN('" . implode("','",$propertyValue) . "')
							WHERE iip.item_id = i.item_id AND iip.item_property_id = ip.item_property_id 
							AND (
								iip.`value` = io.item_property_multi_id
								OR iip.`value` LIKE CONCAT('%,', io.item_property_multi_id, ',%')
								OR iip.`value` LIKE CONCAT('%,', io.item_property_multi_id)
								)
							)";
					} else if(strpos($searchType, "link-") !== false) {
						$searchWheres[] = "1 = (
							SELECT
								COUNT(*)
							FROM
								bgtz4_fl_items i2
							INNER JOIN bgtz4_fl_items_properties ip ON ip.`name` = '" . $propertyName . "'
							INNER JOIN bgtz4_fl_items_item_property iip ON iip.item_property_id = ip.item_property_id
							WHERE iip.item_id = i.item_id AND iip.value = i2.item_id AND iip.item_property_id = ip.item_property_id AND i2.`name` = '" . $propertyValue . "'
							)";
					} else {
						// Check if we have a range
						if(strpos($propertyValue, "-") !== false) {
							$range = explode("-", $propertyValue);
							if($searchType == "date") {
								if(isset($range[0]) && is_numeric($range[0])) {
									$range[0] = date("'Y-m-d'", $range[0]);
								}
								if(isset($range[1]) && $range[1] && is_numeric($range[1])) {
									$range[1] = date("'Y-m-d'", $range[1]);
								} else {
									$range[1] = false;
								}
							}
							$rangeWhere = "( 1 = 1 ";
							if(isset($range[0]) && !empty($range[0])) {
								$rangeWhere .= "AND iip.value >= $range[0] ";
							}
							if(isset($range[1]) && !empty($range[1])) {
								$rangeWhere .= "AND iip.value <= $range[1] ";
							}
							$rangeWhere .= ") ";
							$searchWheres[] = "1 = (
								SELECT COUNT(*) 
								FROM #__fl_items_item_property iip
								INNER JOIN #__fl_items_properties ip ON ip.name = '" . $propertyName . "'
								WHERE iip.item_id = i.item_id AND iip.item_property_id = ip.item_property_id AND $rangeWhere )";
						} else {
							// No range check for exact value
							$searchWheres[] = "1 = (
								SELECT COUNT(*) 
								FROM #__fl_items_item_property iip
								INNER JOIN #__fl_items_properties ip ON ip.name = '" . $propertyName . "'
								WHERE iip.item_id = i.item_id AND iip.item_property_id = ip.item_property_id AND iip.value = '" . $propertyValue . "' )";
						}
					}
				}
			}
		}
		if($parentId) {
			$searchWheres[] = "i.parent_item_id = " . $parentId;
			if($itemCategoryId) {
				$searchWheres[] = "i.item_category_id = " . $itemCategoryId;
			}
		}

		if(count($searchWheres)) {
			$searchWhere = " AND ";
		}
		$searchWhere .= implode(" AND ", $searchWheres);
		
		$categoryWhere = "";
		if(!empty($category)) {
			$categoryWhere = " AND i.item_category_id = " . $category;
		}
		
		$query = "
			SELECT COUNT(*)
			FROM
				#__fl_items i
			INNER JOIN #__fl_items_category c ON c.item_category_id = i.item_category_id
			WHERE
				i.showItem = 1 $categoryWhere $searchWhere
		";
		$query .= "ORDER BY i.ordering, i.item_id";
		
		$db->setQuery( $query, $recordStart, $recordsPerPage );
		$getAllCount = $db->loadResult();

		return $getAllCount;
	}

	function getAll($recordsPerPage = 15, $recordStart = 0, $category = 0, $searchTerms = array(), $parentId = 0, $itemCategoryId = 0, $sortBy = "", $featuredOnly = 0)
	{
		$db =& JFactory::getDBO();
		
		$searchWheres = array();
		$searchWhere = "";
		foreach($searchTerms as $st) {
			if(strpos($st, "_")) {
				$split = explode("_", $st, 2);
				$propertyName = $split[0];
				$propertyValue = $split[1];
				if($propertyName == "search"){
					$searchWheres[] = "(i.name LIKE '%$propertyValue%' OR (0 < (
								SELECT COUNT(*) 
								FROM #__fl_items_item_property iip
								INNER JOIN #__fl_items_properties ip ON ip.isSearchable = 1
								WHERE iip.item_id = i.item_id AND iip.item_property_id = ip.item_property_id AND iip.value LIKE '%" . $propertyValue . "%' )))";
				} else if ($propertyName == "parent") {
					$searchWheres[] = 'i.parent_item_id = (SELECT p.item_id FROM #__fl_items p WHERE p.name="'.$propertyValue.'")';
				} else {
					// check type
					$sql = "SELECT SQL_CALC_FOUND_ROWS type FROM #__fl_items_properties WHERE name = '" . $propertyName . "'";
					$db->setQuery( $sql );
					$searchType = $db->loadResult();
					if($searchType == "select") {
						$propertyValue = explode("|", $propertyValue);
						$searchWheres[] = "1 = (
							SELECT COUNT(*) 
							FROM #__fl_items_item_property iip
							INNER JOIN #__fl_items_properties ip ON ip.name = '" . $propertyName . "'
							INNER JOIN #__fl_items_option io ON io.item_property_id = ip.item_property_id AND io.option IN('" . implode("','",$propertyValue) . "')
							WHERE iip.item_id = i.item_id AND iip.item_property_id = ip.item_property_id AND iip.value = io.item_property_multi_id )";
					} else if($searchType == "multi") {
						$propertyValue = explode("|", $propertyValue);
						$searchWheres[] = "1 = (
							SELECT COUNT(*) 
							FROM #__fl_items_item_property iip
							INNER JOIN #__fl_items_properties ip ON ip.name = '" . $propertyName . "'
							INNER JOIN #__fl_items_option io ON io.item_property_id = ip.item_property_id AND io.option IN('" . implode("','",$propertyValue) . "')
							WHERE iip.item_id = i.item_id AND iip.item_property_id = ip.item_property_id 
							AND (
								iip.`value` = io.item_property_multi_id
								OR iip.`value` LIKE CONCAT('%,', io.item_property_multi_id, ',%')
								OR iip.`value` LIKE CONCAT('%,', io.item_property_multi_id)
								)
							)";
					} else if(strpos($searchType, "link-") !== false) {
						$searchWheres[] = "1 = (
							SELECT
								COUNT(*)
							FROM
								bgtz4_fl_items i2
							INNER JOIN bgtz4_fl_items_properties ip ON ip.`name` = '" . $propertyName . "'
							INNER JOIN bgtz4_fl_items_item_property iip ON iip.item_property_id = ip.item_property_id
							WHERE iip.item_id = i.item_id AND iip.value = i2.item_id AND iip.item_property_id = ip.item_property_id AND i2.`name` = '" . $propertyValue . "'
							)";
					} else {
						// Check if we have a range
						if(strpos($propertyValue, "-") !== false) {
							$range = explode("-", $propertyValue);
							if($searchType == "date") {
								if(isset($range[0]) && is_numeric($range[0])) {
									$range[0] = date("'Y-m-d'", $range[0]);
								}
								if(isset($range[1]) && $range[1] && is_numeric($range[1])) {
									$range[1] = date("'Y-m-d'", $range[1]);
								} else {
									$range[1] = false;
								}
							}
							$rangeWhere = "( 1 = 1 ";
							if(isset($range[0]) && !empty($range[0])) {
								$rangeWhere .= "AND iip.value >= $range[0] ";
							}
							if(isset($range[1]) && !empty($range[1])) {
								$rangeWhere .= "AND iip.value <= $range[1] ";
							}
							$rangeWhere .= ") ";
							$searchWheres[] = "1 = (
								SELECT COUNT(*) 
								FROM #__fl_items_item_property iip
								INNER JOIN #__fl_items_properties ip ON ip.name = '" . $propertyName . "'
								WHERE iip.item_id = i.item_id AND iip.item_property_id = ip.item_property_id AND $rangeWhere )";
						} else {
							// No range check for exact value
							$searchWheres[] = "1 = (
								SELECT COUNT(*) 
								FROM #__fl_items_item_property iip
								INNER JOIN #__fl_items_properties ip ON ip.name = '" . $propertyName . "'
								WHERE iip.item_id = i.item_id AND iip.item_property_id = ip.item_property_id AND iip.value = '" . $propertyValue . "' )";
						}
					}
				}
			}
		}
		if($parentId) {
			$searchWheres[] = "i.parent_item_id = " . $parentId;
			if($itemCategoryId) {
				$searchWheres[] = "i.item_category_id = " . $itemCategoryId;
			}
		}

		if(count($searchWheres)) {
			$searchWhere = " AND ";
		}
		$searchWhere .= implode(" AND ", $searchWheres);
		
		$categoryWhere = "";
		if(!empty($category)) {
			$categoryWhere = " AND i.item_category_id = " . $category;
		}
		$featuredOnlyWhere = "";
		if($featuredOnly) {
			$featuredOnlyWhere = " AND i.isFeatured = 1";
		}
		
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
				i.showItem = 1 $categoryWhere $searchWhere $featuredOnlyWhere
		";
		
		if($sortBy) {
			$sorts = explode(",", $sortBy);
			$cleanSorts = array("i.isFeatured DESC");
			
			foreach($sorts as $thisSort) {
				$split = explode(" ", trim($thisSort));
				if(!isset($split[1])) {
					$split[1] = "ASC";
				}
				if($split[0] == "name") {
					$cleanSorts[] = "i.name $split[1]";
				} else if(strtoupper($split[0]) == "RAND" || strtoupper($split[0]) == "RANDOM") {
					$cleanSorts[] = "RAND()";
				} else if($split[0]) {
					$subQuery = "SELECT type FROM #__fl_items_properties WHERE name = '$split[0]' AND item_category_id = $category";
					$db->setQuery($subQuery);
					$type = $db->loadResult();
					if($type == "number" || $type == "price") {
						$cleanSorts[] = "CAST((SELECT iip.value 
							FROM #__fl_items_item_property iip
							INNER JOIN #__fl_items_properties ip ON ip.name = '" . $split[0] . "'
							WHERE iip.item_id = i.item_id AND iip.item_property_id = ip.item_property_id) AS UNSIGNED) ".$split[1];
					} else {
						$cleanSorts[] = "(SELECT iip.value 
							FROM #__fl_items_item_property iip
							INNER JOIN #__fl_items_properties ip ON ip.name = '" . $split[0] . "'
							WHERE iip.item_id = i.item_id AND iip.item_property_id = ip.item_property_id) ".$split[1];
					}
				}
			}
			$cleanSorts[] = "i.ordering ASC";
			$query .= "ORDER BY " . implode(", ", $cleanSorts);
		} else {
			$query .= "ORDER BY i.isFeatured DESC, i.ordering, i.item_id";
		}
		
		$db->setQuery( $query, $recordStart, $recordsPerPage );
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
		
		$links = array();
		$propIds = array(0);
		foreach($getProps as $prop) {
			$propIds[] = $prop->item_property_id;
			$getAll[$prop->item_id]['property'][$prop->name] = $prop;
			
			if(substr($prop->type, 0, 5) == "link-" ) {
				$newLink = $prop->value;
				if(!in_array($newLink, $links)) {
					$links[] = $newLink;
				}
			}
			if(substr($prop->type, 0, 6) == "mlink-" ) {
				$mLinks = $prop->value;
				$split = explode(",", $mLinks);
				foreach($split as $newLink) {
					if(!in_array($newLink, $links)) {
						$links[] = $newLink;
					}
				}
			}
		}
		$getAll['links'] = $links;
		
		// Get item options
		$query = "
			SELECT
				op.*, p.name, p.caption
			FROM
				#__fl_items_option op
			INNER JOIN #__fl_items_properties p
				ON p.item_property_id = op.item_property_id
			WHERE
				op.item_property_id IN (".implode(",",$propIds).")
		";
		
		$db->setQuery( $query);
		$getOptions = $db->loadObjectList();
		foreach($getOptions as $opt) {
			$getAll['options'][$opt->name][$opt->item_property_multi_id] = $opt->option;
		}
		
		return $getAll;
	}

	function getCategory($category) {
		$db =& JFactory::getDBO();
		$query = "
			SELECT *
			FROM #__fl_items_category
			WHERE item_category_id = $category
		";

		$db->setQuery( $query );

		$getCategory = $db->loadObject();
		
		return $getCategory;
	}

	function getAllFeatured($count = 5, $isRandom = 1)
	{
		$db =& JFactory::getDBO();
		$query = "
			SELECT SQL_CALC_FOUND_ROWS p.*,
			p.item_id as slug
			FROM #__fl_items p
			WHERE p.showItem = 1
				AND p.isFeatured = 1
		";
		if( $isRandom ) {
			$query .= " ORDER BY RAND() ";
		} else {
			$query .= " ORDER BY p.ordering, p.item_category_id ";
		}

		$db->setQuery( $query, 0, $count );

		$getAllFeatured = $db->loadObjectList();
		
		return $getAllFeatured;
	}

	function getOne($alias = "", $item_id = 0, $userId = 0)
	{
		$db =& JFactory::getDBO();
		$query = "
			SELECT p.*, c.`name` AS category, c.`menuId` AS menuId, 
			(SELECT img.filename FROM #__fl_items_image img WHERE img.item_id = p.item_id ORDER BY img.ordering LIMIT 1) AS image,
			parent.name as parentName
			FROM #__fl_items p
			INNER JOIN #__fl_items_category c ON c.item_category_id = p.item_category_id
			LEFT JOIN #__fl_items parent ON parent.item_id = p.parent_item_id
			WHERE p.showItem = 1";
			if($item_id) {
				$query .= " AND p.item_id = ".$db->quote($item_id);
			} else {
				$query .= " AND p.alias = ".$db->quote($alias);
			}
		if($userId) {
			$query .= " AND p.linked_user_id = $userId";
		}
		
		$db->setQuery( $query );
		$getOneItem = $db->loadAssoc();
		$item_id = $getOneItem['item_id'];

		if(!count($getOneItem) || !$item_id) {
			return 0;
		}
		
		// Get Properties
		$query = "
			SELECT ip.*, p.`name`, p.caption, p.type, (SELECT io.option FROM #__fl_items_option io WHERE io.item_property_multi_id = ip.value) AS selectValue
			FROM #__fl_items_item_property ip
			INNER JOIN #__fl_items_properties p ON p.item_property_id = ip.item_property_id
			WHERE 1 = (SELECT COUNT(*) FROM #__fl_items i WHERE i.showItem = 1 AND i.item_id = ip.item_id)
				AND ip.item_id = $item_id
		";
		$db->setQuery( $query);
		$getProps = $db->loadObjectList();
		$links = array();
		
		$propIds = array(0);
		foreach($getProps as $prop) {
			$propIds[] = $prop->item_property_id;
			
			if(substr($prop->type, 0, 5) == "link-" ) {
				$newLink = $prop->value;
				if(!in_array($newLink, $links)) {
					$links[] = $newLink;
				}
			}
			if(substr($prop->type, 0, 6) == "mlink-" ) {
				$newLinkStr = $prop->value;
				$split = explode(",", $newLinkStr);
				if(count($split)) {
					foreach($split as $newLink) {
						if(!in_array($newLink, $links)) {
							$links[] = $newLink;
						}
					}
				}
			}
			if(substr($prop->type, 0, 5) == "subs-" ) {
				$split = explode("-", $prop->type);
				$subParentLink = $split[1];
				$query = "SELECT subItemParentId FROM #__fl_items_category WHERE item_category_id = $subParentLink";
				$db->setQuery( $query);
				$subLinkId = $db->loadResult();
				
				$query = "SELECT item_id FROM #__fl_items WHERE item_category_id = $subLinkId";
				$db->setQuery( $query);
				$getNewSubLinks = $db->loadColumn();
				
				foreach($getNewSubLinks as $nsl) {
					if(!in_array($nsl, $links)) {
						$links[] = $nsl;
					}
				}
			}
			$getCleanProps[$prop->name] = $prop;
		}
		
		// Get item options
		$query = "
			SELECT
				op.*, p.name, p.caption
			FROM
				#__fl_items_option op
			INNER JOIN #__fl_items_properties p
				ON p.item_property_id = op.item_property_id
			WHERE
				op.item_property_id IN (".implode(",",$propIds).")
		";
		
		$db->setQuery( $query);
		$getOptions = $db->loadObjectList();
		foreach($getOptions as $opt) {
			$options[$opt->name][$opt->item_property_multi_id] = $opt->option;
		}
		
		$getOne['item'] = $getOneItem;
		$getOne['property'] = $getCleanProps;
		$getOne['option'] = $options;
		$getOne['links'] = $links;
		
		return $getOne;
	}

	function getOneForEdit($item_id, $userId)
	{
		$db =& JFactory::getDBO();
		$query = "
			SELECT p.*, 
			c.hasImages, 
			c.isSingleImage, 
			c.hasImageCaptions, 
			c.imageWidth, 
			c.imageHeight, 
			c.usersUpdatePublish, 
			c.`name` AS category, 
			c.`menuId` AS menuId, 
			(SELECT img.filename FROM #__fl_items_image img WHERE img.item_id = p.item_id ORDER BY img.ordering LIMIT 1) AS thumbnail
			FROM #__fl_items p
			INNER JOIN #__fl_items_category c ON c.item_category_id = p.item_category_id
			LEFT JOIN #__fl_items parent ON parent.item_id = p.parent_item_id
			WHERE p.item_id = ".$item_id."
		";
		if($userId) {
			$query .= " AND (p.linked_user_id = $userId OR parent.linked_user_id = $userId)";
		}
		$db->setQuery( $query );
		$getOneItem = $db->loadAssoc();

		// If user doesn't have access return null
		if(!$getOneItem) {
			return null;
		}

		$categoryId = $getOneItem['item_category_id'];

		// Get all properties for this item type
		$query = "
			INSERT INTO #__fl_items_item_property (item_id, item_property_id) 
			SELECT '$item_id' AS item_id, p.item_property_id
			FROM #__fl_items_properties p
			WHERE p.item_category_id = $categoryId
				AND 0 = ( 
					SELECT count(*) 
					FROM #__fl_items_item_property iip 
					WHERE iip.item_id = $item_id 
						AND iip.item_property_id = p.item_property_id 
				)
		";

		$db->setQuery($query);
		$db->execute();

		if(!count($getOneItem)) {
			return 0;
		}
		
		// Get Properties
		$query = "
			SELECT ip.*, p.`name`, p.caption, p.type, (SELECT io.option FROM #__fl_items_option io WHERE io.item_property_multi_id = ip.value) AS selectValue
			FROM #__fl_items_item_property ip
			INNER JOIN #__fl_items_properties p ON p.item_property_id = ip.item_property_id
			WHERE 1 = (SELECT COUNT(*) FROM #__fl_items i WHERE i.item_id = ip.item_id)
				AND ip.item_id = $item_id
			ORDER BY p.ordering
		";
		$db->setQuery( $query);
		$getProps = $db->loadObjectList();
		$links = array();

		foreach($getProps as $prop) {
			if(substr($prop->type, 0, 5) == "link-" ) {
				$newLink = $prop->value;
				if(!in_array($newLink, $links)) {
					$links[] = $newLink;
				}
			}
			if(substr($prop->type, 0, 5) == "subs-" ) {
				$split = explode("-", $prop->type);
				$subParentLink = $split[1];
				$query = "SELECT subItemParentId FROM #__fl_items_category WHERE item_category_id = $subParentLink";
				$db->setQuery( $query);
				$subLinkId = $db->loadResult();
				
				$query = "SELECT item_id FROM #__fl_items WHERE item_category_id = $subLinkId";
				$db->setQuery( $query);
				$getNewSubLinks = $db->loadColumn();
				
				foreach($getNewSubLinks as $nsl) {
					if(!in_array($nsl, $links)) {
						$links[] = $nsl;
					}
				}
			}
			// Get Property options
			$query = "
				SELECT item_property_multi_id, `option`, item_property_id
				FROM #__fl_items_option
				WHERE item_property_id = $prop->item_property_id
			";
			$db->setQuery( $query);
			$prop->options = $db->loadObjectList();
			$getCleanProps[$prop->name] = $prop;
		}
		
		// Get item options
		// $query = "
		// 	SELECT opt.`option`, p.`name`, p.caption, o.item_id, o.item_property_id
		// 	FROM #__fl_items_option_map o
		// 	INNER JOIN #__fl_items_properties p ON p.item_property_id = o.item_property_id
		// 	INNER JOIN #__fl_items_option opt ON o.item_property_multi_id = opt.item_property_multi_id
		// 	WHERE 1 = (SELECT COUNT(*) FROM #__fl_items i WHERE i.showItem = 1 AND i.item_id = o.item_id)
		// 		AND o.item_id = $item_id
		// ";
		
		// $db->setQuery( $query);
		// $getOptions = $db->loadObjectList();
		// foreach($getOptions as $opt) {
		// 	$options[$opt->name]['values'][] = $opt->option;
		// 	$options[$opt->name]['caption'] = $opt->caption;
		// }
		
		$getOne['item'] = $getOneItem;
		$getOne['property'] = $getCleanProps;
		$getOne['option'] = $options;
		$getOne['links'] = $links;
		
		return $getOne;
	}

	function createNewTempItem($categoryId, $parentId = 0, $userId = 0) {
		$row = &JTable::getInstance('items', 'FLItemsTable');
		$row->load(0);
		$row->item_category_id = $categoryId;
		$row->linked_user_id = $userId;
		$row->parent_item_id = $parentId;
		$row->name = "New!";
		$row->store();
		return $row->item_id;
	}

	function getEditItems($userId)
	{
		$db =& JFactory::getDBO();
		$query = "
			SELECT p.*, c.`name` AS category, c.`menuId` AS menuId, (SELECT img.filename FROM #__fl_items_image img WHERE img.item_id = p.item_id ORDER BY img.ordering LIMIT 1) AS thumbnail
			FROM #__fl_items p
				INNER JOIN #__fl_items_category c ON c.item_category_id = p.item_category_id
			WHERE p.linked_user_id = $userId
		";
		$db->setQuery( $query );
		$getEditItems = $db->loadAssocList();


		return $getEditItems;
		
		// Get Properties
		$query = "
			SELECT ip.*, p.`name`, p.caption, p.type, (SELECT io.option FROM #__fl_items_option io WHERE io.item_property_multi_id = ip.value) AS selectValue
			FROM #__fl_items_item_property ip
			INNER JOIN #__fl_items_properties p ON p.item_property_id = ip.item_property_id
			WHERE 1 = (SELECT COUNT(*) FROM #__fl_items i WHERE i.item_id = ip.item_id)
				AND ip.item_id = $item_id
		";
		$db->setQuery( $query);
		$getProps = $db->loadObjectList();
		$links = array();

		foreach($getProps as $prop) {
			if(substr($prop->type, 0, 5) == "link-" ) {
				$newLink = $prop->value;
				if(!in_array($newLink, $links)) {
					$links[] = $newLink;
				}
			}
			if(substr($prop->type, 0, 5) == "subs-" ) {
				$split = explode("-", $prop->type);
				$subParentLink = $split[1];
				$query = "SELECT subItemParentId FROM #__fl_items_category WHERE item_category_id = $subParentLink";
				$db->setQuery( $query);
				$subLinkId = $db->loadResult();
				
				$query = "SELECT item_id FROM #__fl_items WHERE item_category_id = $subLinkId";
				$db->setQuery( $query);
				$getNewSubLinks = $db->loadColumn();
				
				foreach($getNewSubLinks as $nsl) {
					if(!in_array($nsl, $links)) {
						$links[] = $nsl;
					}
				}
			}
			$getCleanProps[$prop->name] = $prop;
		}
		
		// Get item options
		// $query = "
			// SELECT opt.`option`, p.`name`, p.caption, o.item_id, o.item_property_id
			// FROM #__fl_items_option_map o
			// INNER JOIN #__fl_items_properties p ON p.item_property_id = o.item_property_id
			// INNER JOIN #__fl_items_option opt ON o.item_property_multi_id = opt.item_property_multi_id
			// WHERE 1 = (SELECT COUNT(*) FROM #__fl_items i WHERE i.item_id = o.item_id)
				// AND o.item_id = $item_id
		// ";
// 		
		// $db->setQuery( $query);
		// $getOptions = $db->loadObjectList();
		// foreach($getOptions as $opt) {
			// $options[$opt->name]['values'][] = $opt->option;
			// $options[$opt->name]['caption'] = $opt->caption;
		// }
		
		$getOne['item'] = $getOneItem;
		$getOne['property'] = $getCleanProps;
		$getOne['option'] = $options;
		$getOne['links'] = $links;
		
		return $getOne;
	}

	function getAllWhere($propertyId, $equalsThisValue) {
		$categoryWhere = "";
		if(!empty($category)) {
			$categoryWhere = " AND i.item_category_id = " . $category;
		}
		$db =& JFactory::getDBO();
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
				i.showItem = 1 $categoryWhere
				AND 1 = (SELECT COUNT(*) FROM #__fl_items_item_property iip WHERE iip.item_id = i.item_id AND iip.item_property_id = $propertyId AND iip.value = $equalsThisValue )
		";
		$query .= "ORDER BY i.ordering, i.item_id";
		
		$db->setQuery( $query, $recordStart, $recordsPerPage );
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
			SELECT ip.*, p.`name`, p.caption
			FROM #__fl_items_item_property ip
			INNER JOIN #__fl_items_properties p ON p.item_property_id = ip.item_property_id
			WHERE 1 = (SELECT COUNT(*) FROM #__fl_items i WHERE i.showItem = 1 AND i.item_id = ip.item_id)
				AND ip.item_id IN ($idList)
		";
		$db->setQuery( $query);
		$getProps = $db->loadObjectList();
		
		foreach($getProps as $prop) {
			$getAll[$prop->item_id]['property'][$prop->name] = $prop;
		}
		
		// Get item options
		// $query = "
			// SELECT opt.`option`, p.`name`, p.caption, o.item_id, o.item_property_id
			// FROM #__fl_items_option_map o
			// INNER JOIN #__fl_items_properties p ON p.item_property_id = o.item_property_id
			// INNER JOIN #__fl_items_option opt ON o.item_property_multi_id = opt.item_property_multi_id
			// WHERE 1 = (SELECT COUNT(*) FROM #__fl_items i WHERE i.showItem = 1 AND i.item_id = o.item_id)
				// AND o.item_id IN ($idList)
		// ";
		// $db->setQuery( $query);
		// $getOptions = $db->loadObjectList();
		// foreach($getOptions as $opt) {
			// $getAll[$opt->item_id]['option'][$opt->name][] = $opt->option;
		// }
		
		return $getAll;
	}
	
	function getAllByParentId($parentId, $itemCategoryId) {
		return FLItemsModelfl_items::getAll(0, 0, 0, array(), $parentId, $itemCategoryId);
	}
	
}
