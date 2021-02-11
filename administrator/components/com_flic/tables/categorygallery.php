<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class FLICTablecategorygallery extends JTable
{
	var $flic_category_gallery_id = null;
	var $flic_id = null;
	var $flic_category_id = null;

	function __construct( &$_db )
	{
		parent::__construct( '#__flic_category_gallery', 'flic_category_gallery_id', $_db );
	}
}
?>
