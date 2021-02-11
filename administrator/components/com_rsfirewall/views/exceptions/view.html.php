<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2020 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RsfirewallViewExceptions extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	public $filterForm;
	public $activeFilters;
	
	public function display($tpl=null)
	{
		if (!JFactory::getUser()->authorise('exceptions.manage', 'com_rsfirewall'))
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
			$app->redirect(JRoute::_('index.php?option=com_rsfirewall', false));
		}
		
		$this->addToolBar();
		
		$this->items 		= $this->get('Items');
		$this->pagination 	= $this->get('Pagination');
		$this->state 		= $this->get('State');

		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		
		parent::display($tpl);
	}
	
	protected function addToolBar()
	{
		RSFirewallToolbarHelper::addToolbar('exceptions');

		// set title
		JToolbarHelper::title('RSFirewall!', 'rsfirewall');

		JToolbarHelper::addNew('exception.add');
		JToolbarHelper::editList('exception.edit');
		JToolbarHelper::divider();
		JToolbarHelper::publish('exceptions.publish', 'JTOOLBAR_PUBLISH', true);
		JToolbarHelper::unpublish('exceptions.unpublish', 'JTOOLBAR_UNPUBLISH', true);
		JToolbarHelper::divider();
		JToolbarHelper::deleteList('COM_RSFIREWALL_CONFIRM_DELETE', 'exceptions.delete');
	}
}