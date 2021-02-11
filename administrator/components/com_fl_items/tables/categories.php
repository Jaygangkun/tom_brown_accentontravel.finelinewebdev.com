<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class FLItemsTableCategories extends JTable
{
	var $item_category_id = null;
	var $parent_category_id = 0;
	var $name = "";
	var $isDescriptionEnabled = 0;
	var $description = "";
	var $ordering = 0;
	var $menuId = 0;
	var $showCategory = 1;
	var $hasImages = 1;
	var $isSingleImage = 0;
	var $hasImageCaptions = 0;
	var $isSubItem = 0;
	var $subItemParentId = 0;
	var $isNewFirst = 0;
	var $isLinkToUser = 0;
	var $isFeaturedEnabled = 0;
	var $imageWidth = 0;
	var $imageHeight = 0;
	var $forceExactImageSize = 0;
	var $usersEditOnly = 0;
	var $usersUpdatePublish = 0;
	var $isForceMenuItem = 0;
	var $isHiddenParent = 0;
	var $isHeader = 0;
	var $noimage = 0;
	var $addWatermark = 0;
	var $watermarkImage = "";
	var $watermarkPosition = 0;

	function __construct( &$_db )
	{
		parent::__construct( '#__fl_items_category', 'item_category_id', $_db );
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
