<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class FLSchemaTableschema extends JTable
{
	var $fl_schema_id = null;
	var $businessType = "";
	var $logo = "";
	var $image = "";
	var $phone = "";
	var $fax = "";
	var $email = "";
	var $address = "";
	var $city = "";
	var $state = "";
	var $zip = "";
	var $latitude = "";
	var $longitude = "";
	var $googleMapLink = "";
	var $areasServed = "";
	var $hours = "";
	var $priceRange = "";
	var $offerCatalog = "";
	var $sameAs = "";

	function __construct( &$_db )
	{
		parent::__construct( '#__fl_schema', 'fl_schema_id', $_db );
	}
}
?>
