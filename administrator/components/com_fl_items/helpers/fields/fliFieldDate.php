<?php

defined('_JEXEC') or die;

class FliFieldDate extends FliField
{
	public function __construct($data) {
		parent::__construct($data);
	}

	public function prepValueForSave($value) {
		if($value == "0000-00-00 00:00:00") {
			$value = "";
		}
		return $value;
	}
	
	protected function getAdminInputField() {
		$date = $this->value;
		$date = date("Y-m-d", strtotime($date));
		$datetest = date("Y-m-d", strtotime($date));
		if(empty($this->value) || empty($date) || $date == "1969-12-31") {
			$date = "";			// $date = date("Y-m-d");
		}
		return JHTML::calendar($date,'val-'.$this->name, 'date', '%Y-%m-%d');
	}
	
	public function output($show = "both", $links = array(), $templateBuilder = null) {
		$formattedValue = date("m/d/Y", strtotime($this->value));
		
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