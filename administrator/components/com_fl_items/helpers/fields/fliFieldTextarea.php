<?php

defined('_JEXEC') or die;

class FliFieldTextarea extends FliField
{
	public function __construct($data) {
		parent::__construct($data);
	}
	
	protected function getAdminInputField() {
		$output = "<textarea style='width: calc(100% - 10px)' rows='6' name='val-$this->name' id='$this->name'>$this->value</textarea>"; 
		
		return $output;
	}
	
	public function output($show = "both", $links = array(), $templateBuilder = null) {
		$formattedValue = nl2br($this->value);
		
		if($show == "bothescape") {
	        return json_encode("<div class='item-property $this->name'><strong>$this->caption:</strong> $formattedValue</div>");
	    } else if($show == "caption") {
	        return $this->caption;
	    } else if($show == "name") {
	        return $this->name;
	    } else if($show == "value") {
	        return $formattedValue;
	    } else if($show == "escape") {
	        return json_encode($formattedValue);
	    } else {
			return "<div class='item-property $this->name'><strong>$this->caption:</strong> $formattedValue</div>";
		}
	}
} 