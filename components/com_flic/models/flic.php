<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class FLICModelflic extends JModelLegacy
{
	function getAllCount() 
	{
		$db =& JFactory::getDBO();
		$query = "SELECT FOUND_ROWS();";
		$db->setQuery( $query );
		$getAllCount = $db->loadResult();

		return $getAllCount;
	}

	function getAll($recordsPerPage = 15, $recordStart = 0)
	{
		$db =& JFactory::getDBO();
		$query = "
			SELECT SQL_CALC_FOUND_ROWS g.*
			FROM #__flic AS g
			WHERE g.showGallery = 1
			ORDER BY g.ordering, g.name
		";
		
		$db->setQuery( $query, $recordStart, $recordsPerPage );
		$getAll = $db->loadObjectList();
		
		return $getAll;
	}

	function getCategoryCount() 
	{
		$db =& JFactory::getDBO();
		$query = "SELECT FOUND_ROWS();";
		$db->setQuery( $query );
		$getAllCount = $db->loadResult();

		return $getAllCount;
	}
	
	function getCategory($recordsPerPage = 15, $recordStart = 0, $category )
	{
		$db =& JFactory::getDBO();
		
		$treeWhere = "";
		
		$category = str_replace(":", "-", $category);
		
		// $query = "SELECT treeLeft, treeRight FROM `#__flic_category` WHERE alias = '" . $category . "'";
		// $db->setQuery( $query );
		// $tree = $db->loadRow();
		// if($category) {
			// $where .= "
				// AND (
					// SELECT COUNT(*)
					// FROM #__flic_category_gallery cg
					// INNER JOIN #__flic_category cat ON cat.flic_category_id = cg.flic_category_id
					// WHERE cg.flic_id = g.flic_id AND cat.treeLeft >= " . $tree[0] . " AND cat.treeRight <= " . $tree[1] . " 
				// ) > 0 
			// ";
		// }
		
		if($category) {
			$where = "
				AND (
					SELECT COUNT(*)
					FROM #__flic_category_gallery cg
					INNER JOIN #__flic_category cat ON cat.flic_category_id = cg.flic_category_id
					WHERE cg.flic_id = g.flic_id AND cat.alias = '$category' 
				) > 0 
			";
		}
			
		
		$query = "
			SELECT SQL_CALC_FOUND_ROWS g.*
			FROM #__flic AS g
			WHERE g.showGallery = 1 
				" . $where . "
			ORDER BY g.isFeatured DESC, g.name
		";
		
		$db->setQuery( $query, $recordStart, $recordsPerPage );
		$getAll = $db->loadObjectList();
		
		return $getAll;
	}
	
	function getSubCategories($category) {
		$category = str_replace(":", "-", $category);
		$db =& JFactory::getDBO();
		if(!$category) {
			$query = "
				SELECT name, alias
				FROM #__flic_category
				WHERE parent_flic_category_id = 0
			";
		} else {
			$query = "
				SELECT name, alias
				FROM #__flic_category
				WHERE parent_flic_category_id = (SELECT flic_category_id FROM #__flic_category WHERE alias = '$category')
			";
		}
		
		$db->setQuery( $query );
		$getAll = $db->loadObjectList();
		
		return $getAll;
	}


	function getOne($project_alias)
	{
		$db =& JFactory::getDBO();
		$query = "
			SELECT g.*, g.alias as projectSlug
				FROM #__flic g
				WHERE g.showGallery = 1 AND g.alias = '".$project_alias."'
				LIMIT 1
		";
		$db->setQuery( $query );
		$getOne = $db->loadAssoc();
		
		return $getOne;
	}
	
}
