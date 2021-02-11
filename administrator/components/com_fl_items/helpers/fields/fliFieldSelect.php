<?php

defined('_JEXEC') or die;

class FliFieldSelect extends FliField
{
	public function __construct($data) {
		parent::__construct($data);
	}
	
	public function getSelectedValue() {
		if($this->options[$this->value]) {
			return $this->options[$this->value];
		}
		return "";
	}
	
	protected function getAdminInputField() {
		$output = "<select class='form-control' name='val-$this->name' id='$this->name'>";
        if(isset($this->options['properties'])) {
        	// Dealing with a Sub-item select.
        	$options = $allOptions[$propertyId];
        }
        foreach($this->options as $opt) {
            $selected = "";
            if($opt->item_property_multi_id == $this->value) {
                $selected = "selected='selected'";
            }
            $output .= '<option '.$selected.' value="'.$opt->item_property_multi_id.'">';
                $output .= $opt->option;
            $output .= "</option>";
        }
        $output .= "</select>";
        return $output;
	}
	
	public function output($show = "both") {
		if(empty($this->value)) {
			return "";
		}
		
		$split = explode(",", $this->value);
		$thisValue = "";
		foreach($split as $s) {
			if($s) {
				if($this->options[$s]) {
					$thisValue .= $this->options[$s];
				}
			}
		}
		
		if(empty($thisValue)) {
			return "";
		}
		
		if($show == "bothescape") {
	        return json_encode("<div class='item-property $this->name'><strong>$this->caption:</strong> $thisValue</div>");
	    } else if($show == "caption") {
	        return $this->caption;
	    } else if($show == "name") {
	        return $this->name;
	    } else if($show == "value") {
	        return $thisValue;
	    } else if($show == "escape") {
	        return json_encode($thisValue);
	    } else {
			return "<div class='item-property $this->name'><strong>$this->caption:</strong> $thisValue</div>";
		}
	}
} 