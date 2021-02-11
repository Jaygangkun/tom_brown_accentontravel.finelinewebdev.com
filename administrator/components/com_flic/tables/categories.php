<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class FLICTableCategories extends JTable
{
	var $flic_category_id = null;
	var $parent_flic_category_id = 0;
	var $name = "";
	var $alias = "";
	var $shortDescription = "";
	var $description = "";
	var $metaTitle = "";
	var $metaKeywords = "";
	var $metaDescription = "";
	var $treeLeft = 0;
	var $treeRight = 0;
	var $treeLevel = 0;
	var $showCategory = 1;
	var $checked_out = 0;
	var $checked_out_time = 0;
	var $editor = '';
	/** @var date */
	var $publish_up				= null;
	/** @var date */
	var $publish_down			= null;

	function __construct( &$_db )
	{
		parent::__construct( '#__flic_category', 'flic_category_id', $_db );
	}
}
?>
