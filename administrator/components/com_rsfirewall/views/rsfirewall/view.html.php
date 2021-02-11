<?php
/**
 * @package        RSFirewall!
 * @copyright  (c) 2009 - 2020 RSJoomla!
 * @link           https://www.rsjoomla.com
 * @license        GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RsfirewallViewRsfirewall extends JViewLegacy
{
	protected $buttons;
	protected $canViewLogs;
	protected $lastLogs;
	protected $logNum;
	protected $lastMonthLogs;
	protected $files;
	protected $renderMap;
	// version info
	protected $version;
	protected $code;

	public function display($tpl = null)
	{
		$this->addToolBar();
		if (!JPluginHelper::isEnabled('system', 'rsfirewall'))
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('COM_RSFIREWALL_WARNING_PLUGIN_DISABLED'), 'notice');
		}

		$this->version     = (string) new RSFirewallVersion;
		$this->canViewLogs = JFactory::getUser()->authorise('logs.view', 'com_rsfirewall');
		$this->code        = $this->get('code');
		$this->files       = $this->get('modifiedFiles');
		$this->renderMap   = $this->renderMap();

		if ($this->canViewLogs)
		{
			$this->logNum        = $this->get('logOverviewNum');
			$this->lastLogs      = $this->get('lastLogs');
			$this->lastMonthLogs = $this->get('lastMonthLogs');
		}

		// Load GeoIP helper class
		require_once JPATH_ADMINISTRATOR . '/components/com_rsfirewall/helpers/geoip/geoip.php';
		$this->geoip = RSFirewallGeoIP::getInstance();

		parent::display($tpl);
	}

	protected function addToolbar()
	{
		// set title
		JToolbarHelper::title('RSFirewall!', 'rsfirewall');

		RSFirewallToolbarHelper::addToolbar();
	}

	protected function showDate($date)
	{
		return JHtml::_('date', $date, 'Y-m-d H:i:s');
	}

	protected function renderMap()
	{
		return ($this->get('CountryBlocking') && $this->get('GeoIPStatus'));
	}
}