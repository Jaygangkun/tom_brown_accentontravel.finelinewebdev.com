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


class JFormFieldIExtResType extends JFormFieldList
{
	protected $type = "IExtResType";
	
	protected $labelININame = "PLG_INTEREXT_FIELD_RESTYPE_LABEL";
	protected $descriptionININame = "PLG_INTEREXT_FIELD_RESTYPE_DESC";
	
	/*
		Overrides the getOptions method to return a list of interext resource tag options.
		Added: 1.0
		Changed:	1.1	- added style, script - block, custom text, regex options, and renamed script to script - src
	*/
	protected function getOptions()
	{
		$options = array();
		
		$options[] = JHtml::_('select.option', '0', 'script - src', 'value', 'text');
		$options[] = JHtml::_('select.option', '1', 'link', 'value', 'text');
		$options[] = JHtml::_('select.option', '2', 'style', 'value', 'text');
		$options[] = JHtml::_('select.option', '3', 'script - block', 'value', 'text');
		$options[] = JHtml::_('select.option', '4', 'Custom Text', 'value', 'text');
		$options[] = JHtml::_('select.option', '5', 'Regex', 'value', 'text');

		return $options;
	}
	
	/*
		Overrides the base formfield getLabel method to hard-coded values to avoid having to specify them in the xml for every entry.
		Added: 1.0
	*/
	protected function getLabel()
	{
		// Initialise variables.
		$label = '';

		if ($this->hidden)
		{
			return $label;
		}

		$text = JText::_($this->labelININame);

		// Build the class for the label.
		$class = 'hasTip';

		// Add the opening label tag and main attributes.
		$label .= '<label id="' . $this->id . '-lbl" for="' . $this->id . '" class="' . $class . '"';

		$label .= ' title="'
			. htmlspecialchars(
				trim($text, ':') . '::' . JText::_($this->descriptionININame),
				ENT_COMPAT, 'UTF-8'
				) 
			. '"';

		$label .= '>' . $text . '</label>';

		return $label;
	}
}
