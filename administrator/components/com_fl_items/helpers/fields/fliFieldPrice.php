<?php

defined('_JEXEC') or die;

class FliFieldPrice extends FliField
{
	public function __construct($data) {
		parent::__construct($data);
	}
	
	protected function getAdminInputField() {
		$output = "<input class='inputbox' type='text' name='val-$this->name' id='$this->name' value=\"" . htmlspecialchars($this->value) . "\"></input>";
		
		return $output;
	}
} 