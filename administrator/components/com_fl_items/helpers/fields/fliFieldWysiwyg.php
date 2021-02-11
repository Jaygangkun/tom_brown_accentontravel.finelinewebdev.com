<?php

defined('_JEXEC') or die;

class FliFieldWysiwyg extends FliField
{
	public function __construct($data) {
		parent::__construct($data);
	}
	
	protected function getAdminInputField() {
		$editor=& JFactory::getEditor();
		$output = $editor->display( 'val-'.$this->name,  $this->value, 'calc(100% - 10px)', '200px', '75', '10' );
		
		return $output;
	}
} 