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

/*
	Custom formfield for Group Action select list.
	Added: 1.1
*/
class JFormFieldIExtGroupAction extends JFormFieldList
{
	protected $type = "IExtGroupAction";
	
	protected $labelININame = "PLG_INTEREXT_FIELD_GROUPACTION_LABEL";
	protected $descriptionININame = "PLG_INTEREXT_FIELD_GROUPACTION_DESC";
	
	protected $sortOptionININame = "PLG_INTEREXT_GROUPACTION_OPT_SORT";
	protected $uniqueOptionININame = "PLG_INTEREXT_GROUPACTION_OPT_UNIQUE";
	
	/*
		Overrides the getOptions method to return a list of interext group options.
		Added: 1.1
	*/
	protected function getOptions()
	{
		$options = array();
		
		$options[] = JHtml::_('select.option', '0', JText::_($this->sortOptionININame), 'value', 'text');
		$options[] = JHtml::_('select.option', '1', JText::_($this->uniqueOptionININame), 'value', 'text');

		return $options;
	}
	
	/*
		Overrides the base formfield getLabel method to hard-coded values to avoid having to specify them in the xml for every entry.
		Added: 1.1
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
