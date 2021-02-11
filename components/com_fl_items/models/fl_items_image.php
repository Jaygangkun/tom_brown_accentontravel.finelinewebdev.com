<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class FLItemsModelfl_items_image extends JModelLegacy
{

	function getAll()
	{
		$db =& JFactory::getDBO();
		$query = "
			SELECT pi.*
			FROM #__fl_items_image pi
			WHERE pi.showImage = 1
			ORDER BY pi.ordering
		";
		$db->setQuery( $query );
		$getImgs = $db->loadObjectList();
		
		$getAll = array();
		foreach($getImgs as $img) {
			$getAll[$img->item_id][] = $img;
		}
		
		return $getAll;
	}

	function getOne($item_id = 0)
	{
		$db =& JFactory::getDBO();
		$query = "
			SELECT pi.*
			FROM #__fl_items_image pi
			WHERE pi.showImage = 1
				AND pi.item_id = ".$item_id."
			ORDER BY pi.ordering
		";
		$db->setQuery( $query );
		$getAll = $db->loadObjectList();
		
		return $getAll;
	}
	
}
