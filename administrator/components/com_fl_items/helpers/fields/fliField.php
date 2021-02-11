<?php

defined('_JEXEC') or die;

class FliField
{
	protected $item_id = 0;
	protected $item_property_id = "";
	protected $item_category_id = "";
	protected $name = "";
	protected $caption = "";
	protected $value = "";
	protected $type = "";
	protected $dimensions = "";
	protected $allowUserEdit = 0;
	protected $options = array();
	
	public function __construct($data) {
		$this->item_id = $data->item_id;
		$this->item_property_id = $data->item_property_id;
		$this->item_category_id = $data->item_category_id;
		$this->name = $data->name;
		$this->caption = $data->caption;
		$this->value = $data->value;
		$this->type = $data->type;
		$this->dimensions = $data->dimensions;
		$this->allowUserEdit = $data->allowUserEdit;
		$this->options = $data->options;
	}
	
	public function output($show = "both") {
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
	    } else if($show == "cleanjs") {
	    	$clean = strip_tags($this->value);
			$clean = str_replace('"', '\"', $clean);
			$clean = str_replace(array("\n", "\r"), ' ', $clean);
	        return $clean;
	    } else {
			return "<div class='item-property $this->name'><strong>$this->caption:</strong> $this->value</div>";
		}
	}
	
	public function getValue() {
		return $this->value;
	}

	public function setValue($newValue) {
		$this->value = $newValue;
	}
	
	public function getItemPropertyId() {
		return $this->item_property_id;
	}
	
	protected function getAdminInputField() {
		$output = "Requires override in custom class!";
		return $output;
	}

	protected function getAdminBatchInputField() {
		return $this->getAdminInputField();
	}
	
	protected function wrapAdminInputField($field) {
		$output = "
		<tr>
			<td width='20%' class='key'>
				<label for='item_category_id'>
					$this->caption:
				</label>
			</td>
			<td width='80%'>
				$field
			</td>
		</tr>";
		
		return $output;
	}
	
	public function outputAdminField() {
		$field = $this->getAdminInputField(); 
		$wrapped = $this->wrapAdminInputField($field);
		return $wrapped;
	}

	public function outputAdminBatchField($count = 0) {
		$field = $this->getAdminBatchInputField($count); 
		// Add count to end of input field's name
		$batchField = preg_replace('/name=[\"\']([a-zA-Z\-]*)[\"\']/', 'name="$1-'.$count.'"', $field);
		$wrapped = $this->wrapAdminInputField($batchField);
		return $wrapped;
	}
	
	public function buildValue($value, $caption = "", $show = "both", $name = "" ) {
		if(empty($value)) {
			return "";
		}
		if($show == "both") {
	        return "<div class='item-property $name'><strong>$caption:</strong> $value</div>";
		} else if($show == "bothescape") {
	        return json_encode("<div class='item-property $name'><strong>$caption:</strong> $value</div>");
	    } else if($show == "caption") {
	        return $caption;
	    } else if($show == "name") {
	        return $name;
	    } else if($show == "value") {
	        return $value;
	    } else if($show == "escape") {
	        return json_encode($value);
	    } else {
	        return "<div class='item-property $name'><strong>$caption:</strong> $value</div>";
		}
	}
	
	public function prepValueForSave($value) {
		return $value;
	}
	
	public function setBatchId($newId) {
		$this->item_id = $newId;
	}
}