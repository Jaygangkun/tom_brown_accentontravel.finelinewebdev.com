<?php

defined('_JEXEC') or die;

class FliFieldInternallink extends FliField
{
	public function __construct($data) {
		parent::__construct($data);
	}
	
	public function output($show = "both") {
		if(empty($this->value)) {
    		return "";
    	}
		if($show == "caption") {
	        return $this->caption;
		}
		if(isset($this->value) && $this->value && is_numeric($this->value)){
			$url = JRoute::_('index.php?Itemid='.$this->value);
			return $url;
		}
	}
	
	protected function getAdminInputField() {
		JFormHelper::loadFieldClass('menuitem');
		$field = new JFormFieldMenuitem();
		$field->setup(new SimpleXMLElement("<field class='inputbox internal-link' name='val-$this->name' id='$this->name' type='menuitem' default='0' label='Select a menu item' description='Select a menu item' />"), $this->value);
		
		$output = $field->renderField(array('hiddenLabel'=>true));
		$output .= "
		<script>
			jQuery('.internal-link').last().prepend('<option value=\"0\">- Select a Menu Item</option>');";
			if(empty($this->value)) {
				$output .= "jQuery('.internal-link').last().val(0);";
			}
		$output .= "</script>
		";
		return $output;
	}
} 