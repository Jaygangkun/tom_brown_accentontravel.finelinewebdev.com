<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class FLItemsTableItems extends JTable
{
	var $item_id = null;
	var $item_category_id = 0;
	var $name = '';
	var $ordering = 0;
	var $isFeatured = 0;
	var $showItem = 1;
	var $parent_item_id = null;
	var $parent_item_variation_id = null;
	var $linked_user_id = 0;

	function __construct( &$_db ) {
		parent::__construct( '#__fl_items', 'item_id', $_db );
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
			$this->setError("Name is required.");
			return false;
		}

		return true;
	}
}
