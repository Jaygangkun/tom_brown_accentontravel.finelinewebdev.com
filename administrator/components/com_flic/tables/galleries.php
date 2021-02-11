<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class FLICTableGalleries extends JTable
{
	var $flic_id = null;
	var $name = '';
	var $alias = '';
	var $shortDescription = '';
	var $description = '';
	var $metaTitle = "";
	var $metaKeywords = "";
	var $metaDescription = "";
	var $isFeatured = 0;
	var $showGallery = 1;
	var $ordering = 0;
	var $checked_out = 0;
	var $checked_out_time = 0;
	var $resizeImageWidth = 0;
	var $resizeImageHeight = 0;
	var $editor = '';
	var $menuItems = '';
	var $captionPositions = '1,2,3,4,5,6,7,8,9';
	/** @var date */
	var $publish_up				= null;
	/** @var date */
	var $publish_down			= null;

	function __construct( &$_db ) {
		parent::__construct( '#__flic', 'flic_id', $_db );
	}

	/**
	 * Overloaded check function
	 *
	 * @access public
	 * @return boolean
	 * @see JTable::check
	 * @since 1.5
	 */
	function check()
	{
		// check for valid name
		if (trim($this->name == '')) {
			$this->setError(JText::_( 'FLP_P_Name' ));
			return false;
		}

		return true;
	}
}
