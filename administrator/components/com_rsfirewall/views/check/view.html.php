<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2020 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RsfirewallViewCheck extends JViewLegacy
{
	protected $accessFile;
	protected $defaultAccessFile;
	protected $isWindows;
	protected $isPHP54;
	protected $offset;
	protected $lastRun;
	
	public function display($tpl = null)
	{
		$user = JFactory::getUser();
		if (!$user->authorise('check.run', 'com_rsfirewall')) {
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
			$app->redirect(JRoute::_('index.php?option=com_rsfirewall', false));
		}
		
		$this->addToolBar();
		
		if ($this->get('IsOldIE')) {
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('COM_RSFIREWALL_IE_WARNING_DESC'), 'notice');
		}
		
		// the access file depends on the OS we're in
		$this->accessFile 		 = $this->get('accessFile');
		$this->defaultAccessFile = $this->get('defaultAccessFile');
		
		// on Windows we need to skip a few things
		$this->isWindows = $this->get('isWindows');
		
		// on PHP 5.4 we need to skip safe_mode
		$this->isPHP54	= $this->get('isPHP54');
		
		// is Xdebug loaded?
		$this->hasXdebug = extension_loaded('xdebug');
		
		$this->offset = $this->get('Offset');
		$this->config = RSFirewallConfig::getInstance();
		
		// Last time the System Check was run
		$this->lastRun = $this->config->get('system_check_last_run');
		
		// Prettify
		if ($this->lastRun) {
			$this->lastRun = JHtml::_('date.relative', $this->lastRun);
		} else {
			$this->lastRun = JText::_('COM_RSFIREWALL_NEVER');
		}
		
		parent::display($tpl);
	}
	
	protected function addToolbar()
	{
		// set title
		JToolbarHelper::title('RSFirewall!', 'rsfirewall');

		RSFirewallToolbarHelper::addToolbar('check');
	}

	protected function getSystemCheckSteps()
	{
		// Build steps array
		$steps = array('checkJoomlaVersion',
			'checkRSFirewallVersion',
			'checkSQLPassword',
			'checkAdminUser',
			'checkFTPPassword',
			'checkSEFEnabled',
			'checkConfigurationIntegrity',
			'checkSession',
			'checkTemporaryFiles',
			'checkHtaccess',
			'checkSessionHandler',
			'checkBackendPassword');

		if (in_array('safebrowsing', $this->config->get('google_apis')))
		{
			$steps[] = 'checkGoogleSafeBrowsing';
		}

		if (in_array('webrisk', $this->config->get('google_apis')))
		{
			$steps[] = 'checkGoogleWebRisk';
		}

		return $steps;
	}

	protected function getServerCheckSteps()
	{
		$steps = array(
			'checkAllowURLInclude',
			'checkOpenBasedir',
			'checkDisableFunctions',
			'checkExposePHP');

		if (!$this->isPHP54)
		{
			array_unshift($steps, 'checkRegisterGlobals');
			array_push($steps, 'checkSafeMode');
		}

		return $steps;
	}

	protected function getFilesCheckSteps()
	{
		$steps = array();

		$steps[] = 'checkCoreFilesIntegrity';
		if (!$this->isWindows)
		{
			$steps[] = 'checkFolderPermissions';
			$steps[] = 'checkFilePermissions';
		}
		$steps[] = 'checkSignatures';

		return $steps;
	}
}