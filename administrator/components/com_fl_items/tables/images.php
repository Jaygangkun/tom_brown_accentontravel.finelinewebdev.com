<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class FLItemsTableImages extends JTable
{
	var $item_image_id = null;
	var $item_id = null;
	var $filename = null;
	var $ordering = 0;
	var $showImage = 1;

	function __construct( &$_db )
	{
		parent::__construct( '#__fl_items_image', 'item_image_id', $_db );
	}
}
?>
