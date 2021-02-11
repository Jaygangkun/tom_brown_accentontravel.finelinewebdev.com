<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2020 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

//keep session alive while editing
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');
JHtml::_('formbehavior.chosen', '.advancedSelect');

if ($this->geoip->php_version_compat)
{
	$this->document->addScriptDeclaration('jQuery(RSFirewall.initCountryBlocking);');
}

JText::script('COM_RSFIREWALL_BACKEND_PASSWORD_LENGTH_ERROR');
JText::script('COM_RSFIREWALL_BACKEND_PASSWORDS_DO_NOT_MATCH');
?>
<?php echo RSFirewallAdapterGrid::sidebar(); ?>
	<form action="<?php echo JRoute::_('index.php?option=com_rsfirewall&view=configuration'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal" enctype="multipart/form-data" autocomplete="off">
	<?php
	foreach ($this->fieldsets as $name => $fieldset)
	{
		// add the tab title
		$this->tabs->addTitle($fieldset->label, $fieldset->name);
		
		// prepare the content
		$this->fieldset =& $fieldset;
		$this->fields 	= $this->form->getFieldset($fieldset->name);

		$template = 'fieldset';

		if (in_array($fieldset->name, array('active_scanner', 'backend_password', 'country_block', 'permissions')))
		{
			$template = $fieldset->name;
		}

		$content = $this->loadTemplate($template);
		
		// add the tab content
		$this->tabs->addContent($content);
	}
	
	// render tabs
	$this->tabs->render();
	?>
		<div>
		<?php echo JHtml::_('form.token'); ?>
		<input type="hidden" name="option" value="com_rsfirewall" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="controller" value="configuration" />
		</div>
	</form>
</div>