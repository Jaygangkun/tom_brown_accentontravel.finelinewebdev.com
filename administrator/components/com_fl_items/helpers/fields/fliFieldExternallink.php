<?php

defined('_JEXEC') or die;

class FliFieldExternallink extends FliField
{
	public function __construct($data) {
		parent::__construct($data);
	}
	
	protected function getAdminInputField() {
		$output = "<input class='inputbox external-link' type='text' name='val-$this->name' id='$this->name' value=\"" . htmlspecialchars($this->value) . "\" placeholder='http://www.google.com'></input>";
		
		return $output;
	}
	
	public function prepValueForSave($value) {
		if($value && strpos($value, "http://") === false && strpos($value, "https://") === false) {
			$value = "http://$value";
		}
		
		return $value;
	}
	
} 