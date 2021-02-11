<?php

defined('_JEXEC') or die;

class FliFieldUser extends FliField
{
	public function __construct($data) {
		parent::__construct($data);
	}
	
	protected function getAdminInputField() {
		$output = "";
		$field = new JFormFieldUser();
		$field->setup(new SimpleXMLElement('<field name="val-'.$this->name.'" type="user" label="User Account" />'), $this->value);
		$output = $field->renderField(array('hiddenLabel'=>true));
		
		return $output;
	}
} 