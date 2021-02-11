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

//jimport('joomla.html.html');
//jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('menuitem');

// Import the com_menus helper.
require_once realpath(JPATH_ADMINISTRATOR . '/components/com_menus/helpers/menus.php');

class JFormFieldIExtMenuItems extends JFormFieldMenuItem
{
	public $type = "IExtMenuItems";
	
	protected $labelININame = "PLG_INTEREXT_FIELD_GROUPMENUITEMS_LABEL";
	protected $descriptionININame = "PLG_INTEREXT_FIELD_GROUPMENUITEMS_DESC";
	
	protected $size = 25;
	
	/*
		The setup method needs to be overridden here to set the multiple attribute, so that this formfield is setup correctly for saving multiple values to/from the database.
		Significant changes have been made - note to self, refer back to the base setup method in formfield if making any changes to this method.
		Added: 1.0
	*/
	public function setup(&$element, $value, $group = null)
	{
		// Make sure there is a valid JFormField XML element.
		if (!($element instanceof JXMLElement) || (string) $element->getName() != 'field')
		{
			return false;
		}

		// Reset the input and label values.
		$this->input = null;
		$this->label = null;

		// Set the XML element object.
		$this->element = $element;

		// Get some important attributes from the form field element.
		$class = (string) $element['class'];
		$id = (string) $element['id'];
		$name = (string) $element['name'];
		
		// Set the required and validation options.
		$this->required = false; 
		$this->validate = (string) $element['validate'];

		// Set the multiple values option.
		$this->multiple = true;

		// Allow for field classes to force the multiple values option.
		if (isset($this->forceMultiple))
		{
			$this->multiple = (bool) $this->forceMultiple;
		}

		// Set the field description text
		// needed?
		$this->description = $this->descriptionININame;

		// Set the visibility.
		$this->hidden = false; 

		// Determine whether to translate the field label and/or description.
		$this->translateLabel = true;
		$this->translateDescription = true;

		// Set the group of the field.
		$this->group = $group;

		// Set the field name and id.
		$this->fieldname = $this->getFieldName($name);
		$this->name = $this->getName($this->fieldname);
		$this->id = $this->getId($id, $this->fieldname);

		// Set the field default value.
		$this->value = $value;

		return true;
	}
	
	/*
		Overrides the getInput method for this custom formfield. Enables multiple by default.
		Added: 1.0
	*/
	protected function getInput()
	{
		// Initialize variables.
		$html = array();
		$attr = '';

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$attr .= $this->element['size'] ? ' size="' . (string) $this->element['size'] . '"' : ' size="'. $this->size . '"';
		// we do want to enable multiple select
		$attr .= ' multiple="multiple"';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		// Get the field groups.
		$groups = (array) $this->getGroups();

		$html[] = JHtml::_(
			'select.groupedlist', $groups, $this->name,
			array(
				'list.attr' => $attr, 'id' => $this->id, 'list.select' => $this->value, 'group.items' => null, 'option.key.toHtml' => false,
				'option.text.toHtml' => false
			)
		);
		
		return implode($html);
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
		
		// Add the opening label tag and main attributes attributes.
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
