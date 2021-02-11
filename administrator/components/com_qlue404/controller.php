<?php

// no direct access
defined('_JEXEC') or die('Restricted Access');

class Qlue404Controller extends JControllerLegacy {

	/**
	 * @var		string	The default view.
	 * @since   1.6
	 */
	protected $default_view = 'customs';

	/**
	 * Method to display a view.
	 *
	 * @param   boolean			If true, the view output will be cached
	 * @param   array  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController		This object to support chaining.
	 *
	 * @since   1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		// check if our 404 plugin is enabled
		if(!JPluginHelper::isEnabled('system', 'qlue404')) {
			JError::raiseNotice(500, JText::_('COM_QLUE404_PLUGIN_DISABLED'));	
		}

		$view   = $this->input->get('view', 'customs');
		$layout = $this->input->get('layout', 'default');
		$id     = $this->input->getInt('id');

		parent::display();

		return $this;
	}	
	
}

?>