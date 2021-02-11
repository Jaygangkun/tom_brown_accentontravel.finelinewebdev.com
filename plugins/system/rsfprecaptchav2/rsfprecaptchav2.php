<?php
/**
* @package RSform!Pro
* @copyright (C) 2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die;

define('RSFORM_FIELD_RECAPTCHAV2', 2424);

class plgSystemRsfprecaptchav2 extends JPlugin
{
	protected $autoloadLanguage = true;
	
	// Show field in Form Components
	public function rsfp_bk_onAfterShowComponents() {
		$input 		= JFactory::getApplication()->input;
		$formId 	= $input->getInt('formId');
		$exists 	= RSFormProHelper::componentExists($formId, RSFORM_FIELD_RECAPTCHAV2);
		$link		= $exists ? "displayTemplate('" . RSFORM_FIELD_RECAPTCHAV2 . "', '{$exists[0]}')" : "displayTemplate('" . RSFORM_FIELD_RECAPTCHAV2 ."')";
		
		?>
		<li><a href="javascript: void(0);" onclick="<?php echo $link;?>;return false;" id="rsfpc<?php echo RSFORM_FIELD_RECAPTCHAV2; ?>"><span class="rsficon rsficon-spinner9"></span><span class="inner-text"><?php echo JText::_('RSFP_RECAPTCHAV2_LABEL'); ?></span></a></li>
		<?php
	}

	// Show the Configuration tab
	public function rsfp_bk_onAfterShowConfigurationTabs($tabs) {		
		$tabs->addTitle(JText::_('RSFP_RECAPTCHAV2_LABEL'), 'form-recaptcha-v2');
		$tabs->addContent($this->showConfigurationScreen());
	}
	
	protected function showConfigurationScreen() {
		ob_start();
		?>
		<div id="page-recaptchav2">
			<p><a href="https://www.google.com/recaptcha/" target="_blank"><?php echo JText::_('RSFP_RECAPTCHAV2_GET_RECAPTCHA_HERE'); ?></a></p>
			<table class="admintable">
				<tr>
					<td width="200" style="width: 200px;" align="right" class="key"><label for="recaptchav2sitekey"><?php echo JText::_('RSFP_RECAPTCHAV2_SITE_KEY'); ?></label></td>
					<td><input type="text" name="rsformConfig[recaptchav2.site.key]" id="recaptchav2sitekey" value="<?php echo RSFormProHelper::htmlEscape(RSFormProHelper::getConfig('recaptchav2.site.key')); ?>" size="100" maxlength="100" /></td>
				</tr>
				<tr>
					<td width="200" style="width: 200px;" align="right" class="key"><label for="recaptchav2secretkey"><?php echo JText::_('RSFP_RECAPTCHAV2_SECRET_KEY'); ?></label></td>
					<td><input type="text" name="rsformConfig[recaptchav2.secret.key]" id="recaptchav2secretkey" value="<?php echo RSFormProHelper::htmlEscape(RSFormProHelper::getConfig('recaptchav2.secret.key')); ?>" size="100" maxlength="100" /></td>
				</tr>
				<tr>
					<td width="200" style="width: 200px;" align="right" class="key"><label for="recaptchav2language"><?php echo JText::_('RSFP_RECAPTCHAV2_LANGUAGE'); ?></label></td>
					<td>
						<select name="rsformConfig[recaptchav2.language]" id="recaptchav2language">
							<?php echo JHtml::_('select.options',
									array(
										JHtml::_('select.option', 'auto', JText::_('RSFP_RECAPTCHAV2_LANGUAGE_AUTO')),
										JHtml::_('select.option', 'site', JText::_('RSFP_RECAPTCHAV2_LANGUAGE_SITE'))
									),
								'value', 'text', RSFormProHelper::getConfig('recaptchav2.language'));
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td width="200" style="width: 200px;" align="right" class="key">
						<span class="hasTip" title="<?php echo JText::_('RSFP_RECAPTCHAV2_NOSCRIPT_DESC'); ?>"><?php echo JText::_('RSFP_RECAPTCHAV2_NOSCRIPT'); ?></span>
					</td>
					<td><?php echo RSFormProHelper::renderHTML('select.booleanlist', 'rsformConfig[recaptchav2.noscript]', null, RSFormProHelper::getConfig('recaptchav2.noscript')); ?></td>
				</tr>
				<tr>
					<td width="200" style="width: 200px;" align="right" class="key">
						<span class="hasTip" title="<?php echo JText::_('RSFP_RECAPTCHAV2_ASYNC_DEFER_DESC'); ?>"><?php echo JText::_('RSFP_RECAPTCHAV2_ASYNC_DEFER'); ?></span>
					</td>
					<td><?php echo RSFormProHelper::renderHTML('select.booleanlist', 'rsformConfig[recaptchav2.asyncdefer]', null, RSFormProHelper::getConfig('recaptchav2.asyncdefer')); ?></td>
				</tr>
			</table>
		</div>
		<?php
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}
	
	public function rsfp_f_onAJAXScriptCreate($args)
	{
		$script =& $args['script'];
		$formId = $args['formId'];
		
		if ($componentId = RSFormProHelper::componentExists($formId, RSFORM_FIELD_RECAPTCHAV2))
		{
			$form = RSFormProHelper::getForm($formId);

			$logged	= $form->RemoveCaptchaLogged ? JFactory::getUser()->id : false;

			$data = RSFormProHelper::getComponentProperties($componentId[0]);
			
			if (!empty($data['SIZE']) && $data['SIZE'] == 'INVISIBLE' && !$logged)
			{
				$script .= 'ajaxValidationRecaptchaV2(task, formId, data, '.$componentId[0].');'."\n";
			}
		}
	}
	
	public function rsfp_f_onAfterFormProcess($args)
	{
		$formId = $args['formId'];
		
		if (RSFormProHelper::componentExists($formId, RSFORM_FIELD_RECAPTCHAV2)) {
			JFactory::getSession()->clear('com_rsform.recaptchav2Token'.$formId);
		}
	}

	public function rsfp_f_onInitFormDisplay($args)
	{
		if ($componentIds = RSFormProHelper::componentExists($args['formId'], RSFORM_FIELD_RECAPTCHAV2))
		{
			$all_data = RSFormProHelper::getComponentProperties($componentIds);

			if ($all_data)
			{
				foreach ($all_data as $componentId => $data)
				{
					$args['formLayout'] = preg_replace('/<label (.*?) for="' . preg_quote($data['NAME'], '/') .'"/', '<label $1', $args['formLayout']);
				}
			}
		}
	}
}