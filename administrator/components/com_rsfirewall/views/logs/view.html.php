<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2020 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RsfirewallViewLogs extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $levels;

	public $filterForm;
	public $activeFilters;
	
	public function display( $tpl = null )
	{
		$user = JFactory::getUser();
		if (!$user->authorise('logs.view', 'com_rsfirewall'))
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
			$app->redirect(JRoute::_('index.php?option=com_rsfirewall', false));
		}
		
		$this->addToolBar();

		$this->items 		= $this->get('Items');
		$this->pagination 	= $this->get('Pagination');
		$this->state 		= $this->get('State');
		$this->levels		= $this->get('Levels');

		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		
		// Load GeoIP helper class
		require_once JPATH_ADMINISTRATOR.'/components/com_rsfirewall/helpers/geoip/geoip.php';
		$this->geoip = RSFirewallGeoIP::getInstance();
		
		parent::display($tpl);
	}
	
	protected function addToolBar()
	{
		RSFirewallToolbarHelper::addToolbar('logs');

		// set title
		JToolbarHelper::title('RSFirewall!', 'rsfirewall');
		
		JToolbarHelper::addNew('logs.addtoblacklist', JText::_('COM_RSFIREWALL_LOG_ADD_BLACKLIST'), true);
		JToolbarHelper::addNew('logs.addtowhitelist', JText::_('COM_RSFIREWALL_LOG_ADD_WHITELIST'), true);
		JToolbarHelper::deleteList('COM_RSFIREWALL_CONFIRM_DELETE', 'logs.delete');
		JToolbarHelper::divider();
		JToolbarHelper::custom('logs.truncate', 'delete', 'delete', JText::_('COM_RSFIREWALL_EMPTY_LOG'), false, false);
		JToolbarHelper::custom('logs.download', 'download', 'download', JText::_('COM_RSFIREWALL_DOWNLOAD_LOG'), false, false);
	}
	
	protected function showDate($date)
	{
		return JHtml::_('date', $date, 'Y-m-d H:i:s');
	}
}