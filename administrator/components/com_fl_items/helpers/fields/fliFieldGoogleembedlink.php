<?php

defined('_JEXEC') or die;

class FliFieldGoogleembedlink extends FliField
{
	public function __construct($data) {
		parent::__construct($data);
	}
	
	protected function getAdminInputField() {
		$output = "<input class='inputbox external-link' type='text' name='val-$this->name' id='$this->name' value=\"" . htmlspecialchars($this->value) . "\" placeholder='http://www.google.com'></input>";
		
		return $output;
	}
	
	public function prepValueForSave($value) {
		if(strpos($value, "http://") === false && strpos($value, "https://") === false) {
			$value = "http://$value";
		}
		
		return $value;
	}
	
	public function output($show = "both") {
		if(empty($this->value)) {
			return "";
		}
		
		return '<div class="goole-maps-embed"><iframe src="'.$this->value.'" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen=""></iframe></div>';
	}
	
} 