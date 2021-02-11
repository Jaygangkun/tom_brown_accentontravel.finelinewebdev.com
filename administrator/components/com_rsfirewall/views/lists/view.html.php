<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2020 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RsfirewallViewLists extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $geoip;

	public $filterForm;
	public $activeFilters;

	public function display($tpl = null) {
		$user = JFactory::getUser();
		if (!$user->authorise('lists.manage', 'com_rsfirewall')) {
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
			$app->redirect(JRoute::_('index.php?option=com_rsfirewall', false));
		}
		
		$this->addToolBar();

		$this->state 		 = $this->get('State');
		$this->items 		 = $this->get('Items');
		$this->pagination 	 = $this->get('Pagination');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		
		// Load GeoIP helper class
		require_once JPATH_ADMINISTRATOR.'/components/com_rsfirewall/helpers/geoip/geoip.php';
		$this->geoip = RSFirewallGeoIP::getInstance();
		
		parent::display($tpl);
	}
	
	protected function addToolBar()
	{
		RSFirewallToolbarHelper::addToolbar('lists');

		// set title
		JToolbarHelper::title('RSFirewall!', 'rsfirewall');
		
		JToolbarHelper::addNew('list.add');
		JToolbarHelper::addNew('list.bulkadd', JText::_('COM_RSFIREWALL_BULK_ADD'));

		JToolbarHelper::editList('list.edit');
		JToolbarHelper::divider();
		JToolbarHelper::publish('lists.publish', 'JTOOLBAR_PUBLISH', true);
		JToolbarHelper::unpublish('lists.unpublish', 'JTOOLBAR_UNPUBLISH', true);
		JToolbarHelper::divider();
		JToolbarHelper::deleteList('COM_RSFIREWALL_CONFIRM_DELETE', 'lists.delete');
	}

	private function createButtons()
	{
		$toolbar = JToolbar::getInstance('toolbar');
		$dropdown = $toolbar->dropdownButton('status-group')
			->text('JTOOLBAR_CHANGE_STATUS')
			->toggleSplit(false)
			->icon('fa fa-ellipsis-h')
			->buttonClass('btn btn-action')
			->listCheck(true);

		$childBar = $dropdown->getChildToolbar();

		$childBar->edit('list.edit')->listCheck(true);
		$childBar->publish('lists.publish')->listCheck(true);
		$childBar->unpublish('lists.unpublish')->listCheck(true);
		$childBar->trash('lists.delete')->listCheck(true);
	}
}