<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2020 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RsfirewallViewException extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $field;
	protected $ip;
	
	public function display($tpl = null)
	{
		$user = JFactory::getUser();
		if (!$user->authorise('exceptions.manage', 'com_rsfirewall'))
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
			$app->redirect(JRoute::_('index.php?option=com_rsfirewall', false));
		}
		
		$this->form	= $this->get('Form');
		$this->item	= $this->get('Item');
		$this->ip	= $this->get('IP');

		JFactory::getApplication()->input->set('hidemainmenu', true);

		$this->addToolBar();
		
		parent::display($tpl);
	}
	
	protected function addToolBar()
	{
		RSFirewallToolbarHelper::addToolbar('exceptions');

		// set title
		JToolbarHelper::title('RSFirewall!', 'rsfirewall');
		
		$layout = $this->getLayout();
		switch ($layout)
		{
			case 'edit':
				JToolbarHelper::title($this->item->id ? JText::_('COM_RSFIREWALL_EDITING_EXCEPTION') : JText::_('COM_RSFIREWALL_ADDING_NEW_EXCEPTION'), 'rsfirewall');

				JToolbarHelper::apply('exception.apply');
				JToolbarHelper::save('exception.save');
				JToolbarHelper::save2new('exception.save2new');
				JToolbarHelper::save2copy('exception.save2copy');
				JToolbarHelper::cancel('exception.cancel');
			break;
			
			case 'bulk':
				JToolbarHelper::save('exception.bulksave');
				JToolbarHelper::cancel('exception.cancel');
			break;
		}
	}
}