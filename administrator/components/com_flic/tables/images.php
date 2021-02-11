<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class FLICTableImages extends JTable
{
	var $flic_image_id = null;
	var $flic_id = null;
	var $filename = null;
	var $captionTitle = '';
    var $captionMessage = '';
    var $altTag = '';
	var $url = '';
	var $newWindow = 0;
	var $messagePosition = 1;
	var $ordering = 0;
	var $showGalleryImage = 1;

	function __construct( &$_db )
	{
		parent::__construct( '#__flic_image', 'flic_image_id', $_db );
	}
}
?>
