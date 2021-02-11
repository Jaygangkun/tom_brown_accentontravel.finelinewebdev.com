<?php

defined('_JEXEC') or die;

class FliFieldFile extends FliField
{
	public function __construct($data) {
		parent::__construct($data);
	}
	
	public function output($show = "both") {
		if(empty($this->value)) {
    		return "";
    	}
		$path = "/images/fl_items/files/" . $this->item_id . "/original/" . $this->value;
    	if($show == "path") {
    		return $path;
    	}
		if(exif_imagetype(JPATH_BASE.$path)) { // If this is an image show it ELSE return the path
    		return "<image src='$path'>";
		} else { 
			return $path;
		}
	}
	
	protected function getAdminInputField() {
		$output = "";
		$output .= '<div class="alert alert-danger pull-right">';
    		$output .= '
    		<label class="checkbox" onchange="">
	      		<input type="checkbox" class="delete-file-checkbox"> Delete
		    </label>';
			$output .= '<input type="hidden" name="val-' . $this->name . '" id="val-' . $this->name . '" value="" disabled="disabled" />';
		$output .= '</div>';
    	if(!empty($this->value)) {
    		if(strpos(strtolower($this->value), ".png") || strpos(strtolower($this->value), ".jpg")) {
    			$output .= '<img style="max-width: 100px;" src="/images/fl_items/files/' . $this->item_id . "/original/" . $this->value . '"><br>';
    		}
    		$output .= strlen($this->value) ? $this->value . "<br>" : "";
    	}
    	$output .= '<input class="inputbox" type="file" name="file-' . $this->name . '" id="file-' . $this->name . '" />';
		
		return $output;
	}
} 