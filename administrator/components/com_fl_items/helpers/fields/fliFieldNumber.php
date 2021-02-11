<?php

defined('_JEXEC') or die;

class FliFieldNumber extends FliField
{
	public function __construct($data) {
		parent::__construct($data);
	}
	
	protected function getAdminInputField() {
		$output = "<input class='inputbox' type='number' name='val-$this->name' id='$this->name' value=\"" . htmlspecialchars($this->value) . "\"></input>";
		
		return $output;
	}
	
	public function prepValueForSave($value) {
		if(is_nan($value)) {
			$value = '';
		}
		
		return $value;
	}
} 

