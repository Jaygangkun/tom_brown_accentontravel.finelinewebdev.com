<?php

defined('_JEXEC') or die;

class FliFieldMulti extends FliField
{
	public function __construct($data) {
		parent::__construct($data);
	}
	
	protected function getAdminInputField() {
		$currentVals = explode(",", $this->value);
		$output = "<div class='multi-select' style='-webkit-column-count: 3; -moz-column-count: 3; column-count: 3;'>";
		$output .= '<input type="hidden" name="val-'.$this->name.'[]" value="0" >';
		foreach($this->options as $opt) {
			$selected = "";
			if(in_array($opt->item_property_multi_id, $currentVals)) {
				$selected = "checked";
			}
			$output .= '<div class="clearfix"><label class="checkbox">';
				$output .= '<input style="position: absolute;" type="checkbox" name="val-'.$this->name.'[]" value="'.$opt->item_property_multi_id.'" ' . $selected . ' />' . $opt->option;
			$output .= "</label></div>";
		}
		$output .= "</div>";
		return $output;
	}
	
	public function prepValueForSave($value) {
		if(is_array($value)) {
			$value = implode(",", $value);
		}
		
		return $value;
	}
	
	public function output($show = "both") {
		if(empty($this->value)) {
			return "";
		}
		
		$split = explode(",", $this->value);
		$this->value = "";
		foreach($split as $s) {
			if($s) {
				if($this->options[$s]) {
					$this->value .= "<div class='multi-select'>".$this->options[$s]."</div>";
				}
			}
		}
		
		if(empty($this->value)) {
			return "";
		}
		
		if($show == "bothescape") {
	        return json_encode("<div class='item-property $this->name'><strong>$this->caption:</strong> $this->value</div>");
	    } else if($show == "caption") {
	        return $this->caption;
	    } else if($show == "name") {
	        return $this->name;
	    } else if($show == "value") {
	        return $this->value;
	    } else if($show == "escape") {
	        return json_encode($this->value);
	    } else {
			return "<div class='item-property $this->name'><strong>$this->caption:</strong> $this->value</div>";
		}
	}
} 