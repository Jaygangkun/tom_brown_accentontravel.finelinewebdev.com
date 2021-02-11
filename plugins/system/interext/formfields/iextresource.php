<?php
/**
 * InterExt Plugin
 * Plugin Version 1.1 - Joomla! Version 1.7
 * Author: Chris Burgess
 * chris.burgess@acuit.com.au
 * http://www.acuit.com.au
 * Copyright (c) 2011 AcuIT. All Rights Reserved. 
 * License: GNU/GPL 2, http://www.gnu.org/licenses/gpl-2.0.html
 */
 
defined( '_JEXEC' ) or die('Direct Access to this location is not allowed.');


class JFormFieldIExtResource extends JFormFieldText
{
	protected $type = "IExtResource";
	
	protected $labelININameStart = "PLG_INTEREXT_FIELD_RES";
	
	protected $size = 60;
	
	/*
		Overrides teh getInput method to produce the label for this formfield. Uses size property of this object.
		Added: 1.0
	*/
	protected function getInput()
	{
		// Initialize some field attributes.
		$size = ' size="' . $this->size .'"';
		$maxLength = $this->element['maxlength'] ? ' maxlength="' . (int) $this->element['maxlength'] . '"' : '';
		$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';

		// Initialize JavaScript field attributes.
		$onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		return '<input type="text" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
			. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . $class . $size . $onchange . $maxLength . '/>';
	}
	
	
	/*
		Overrides the base formfield getLabel method to hard-coded values to avoid having to specify them in the xml for every entry.
		Added: 1.0
	*/
	protected function getLabel()
	{
		// Initialise variables.
		$label = '';

		$index = $this->element['index'];
		$text = JText::_($this->labelININameStart . (string) $index . "_LABEL");
		
		// Add the opening label tag and main attributes attributes.
		$label .= '<label id="' . $this->id . '-lbl" for="' . $this->id . '"';

		// Add the label text and closing tag.
		$label .= '>' . $text . '</label>';

		return $label;
	}
}
