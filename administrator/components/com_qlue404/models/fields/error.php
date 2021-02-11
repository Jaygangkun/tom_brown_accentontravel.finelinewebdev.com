<?php 

// No Direct Access
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldError extends JFormField {
	
	// Set the name of this custom parameter
	public $type = 'Error';
	
	public function getInput() {
		
		// Get an instance of JDatabase
		$db = JFactory::getDbo();

		// Create query to find all published custom error page
		$query = 'SELECT `id`, `title` FROM #__qlue404 '
		       . 'WHERE `published` = 1';
		
		// Set the query
		$db->setQuery($query);
		
		// Find all the options
		$options = $db->loadObjectList();
		
		// Set an array of options for our select box
		array_unshift($options, JHTML::_('select.option', '0', '- '.JText::_('COM_QLUE404_MENU_CUSTOM').' -', 'id', 'title'));
		
		// Now add to our select box
		return JHtml::_('select.genericlist', $options, $this->name, 'class="inputbox"', 'id', 'title', $this->value);
	}
}

?>