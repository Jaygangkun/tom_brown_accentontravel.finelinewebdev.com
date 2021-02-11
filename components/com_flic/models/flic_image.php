<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class FLICModelflic_image extends JModelLegacy
{

	function getAll($project_id, $order = "ASC", $totalSlideCount = 0)
	{
		if($order == "RAND") {
			$ordering = "RAND()";
		} else {
			$ordering = "i.ordering $order";
		}
		$db =& JFactory::getDBO();
		$query = "
			SELECT i.*
			FROM #__flic_image AS i
			WHERE i.showGalleryImage = 1 AND flic_id = $project_id
			ORDER BY $ordering
		";
		if($totalSlideCount) {
			$query .= "LIMIT $totalSlideCount";
		}
		
		$db->setQuery( $query );
		$getAll = $db->loadObjectList();
		
		return $getAll;
	}
	
	function getAllGalleryImages($projectIdString)
	{
		$getAllArray = array();
		
		if($projectIdString) {
			$db =& JFactory::getDBO();
			$query = "
				SELECT pi.*
				FROM #__flic_image pi
				WHERE pi.showGalleryImage = 1
					AND pi.flic_id IN (".$projectIdString.")
				ORDER BY pi.ordering
			";
			$db->setQuery( $query );
			$getAll = $db->loadObjectList();
			
			foreach($getAll as $getOne) {
				$getAllArray[$getOne->flic_id][] = $getOne;
			}
		}
		
		return $getAllArray;
	}
	
}
