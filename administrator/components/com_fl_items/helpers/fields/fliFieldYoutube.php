<?php

defined('_JEXEC') or die;

class FliFieldYoutube extends FliField
{
	public function __construct($data) {
		parent::__construct($data);
	}
	
	public function output($show = "both") {
		if(empty($this->value)) {
    		return "";
    	}
		
		$url = $this->value;
		$subSplit = explode("/", $url);
		$shortCode = $subSplit[count($subSplit) - 1];
		$shortCode = str_replace("&feature=youtu.be", "", $shortCode);
		$shortCode = str_replace("watch?v=", "", $shortCode);
		
		return "<div class='video-wrapper'><iframe width='100%' height='315' src='https://www.youtube.com/embed/$shortCode?rel=0' frameborder='0' allowfullscreen></iframe></div>";
	}
	
	protected function getAdminInputField() {
		$output = "<input class='inputbox' type='text' name='val-$this->name' id='$this->name' value=\"" . htmlspecialchars($this->value) . "\"></input>";
		
		return $output;
	}
} 