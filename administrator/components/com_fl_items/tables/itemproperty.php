<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class FLItemsTableItemproperty extends JTable
{
	var $items_item_property_id = null;
	var $item_id = "";
	var $item_property_id = "";
	var $value = "";

	function __construct( &$_db )
	{
		parent::__construct( '#__fl_items_item_property', 'items_item_property_id', $_db );
	}
}
	
?>
