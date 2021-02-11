<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class FLItemsTableProperties extends JTable
{
	var $item_property_id = null;
	var $item_category_id = 0;
	var $name = "";
	var $caption = "";
	var $type = "";
	var $showInDirectory = 0;
	var $enableProperty = 1;
	var $enableFilter = 0;
	var $allowUserEdit = 1;
	var $isSearchable = 0;
	var $dimensions = "";
	var $includeOnForm = 1;

	function __construct( &$_db )
	{
		parent::__construct( '#__fl_items_properties', 'item_property_id', $_db );
	}
	
	function check()
	{
		// check for valid name
		if (trim($this->name == '')) {
			$this->setError("Name is required.");
			return false;
		}

		return true;
	}
}
?>
