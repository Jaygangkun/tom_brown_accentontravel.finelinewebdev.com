<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2020 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.keepalive');

JText::script('COM_RSFIREWALL_ERROR_CHECK');
JText::script('COM_RSFIREWALL_ERROR_CHECK_RETRYING');
JText::script('COM_RSFIREWALL_ERROR_FIX');
JText::script('COM_RSFIREWALL_CONFIGURATION_LINE');
JText::script('COM_RSFIREWALL_MORE_FOLDERS');
JText::script('COM_RSFIREWALL_MORE_FILES');
JText::script('COM_RSFIREWALL_RENAME_ADMIN');

JText::script('COM_RSFIREWALL_BUTTON_FAILED');
JText::script('COM_RSFIREWALL_BUTTON_PROCESSING');
JText::script('COM_RSFIREWALL_BUTTON_SUCCESS');

JText::script('COM_RSFIREWALL_CONFIRM_OVERWRITE_LOCAL_FILE');
JText::script('COM_RSFIREWALL_DOWNLOAD_ORIGINAL');
JText::script('COM_RSFIREWALL_HASHES_CORRECT');
JText::script('COM_RSFIREWALL_HASHES_INCORRECT');
JText::script('COM_RSFIREWALL_VIEW_DIFF');
JText::script('COM_RSFIREWALL_FILE_HAS_BEEN_MODIFIED');
JText::script('COM_RSFIREWALL_FILE_HAS_BEEN_MODIFIED_AGO');
JText::script('COM_RSFIREWALL_FILE_IS_MISSING');
JText::script('COM_RSFIREWALL_FILE_HAS_BEEN_IGNORED');
JText::script('COM_RSFIREWALL_UNIGNORE_BUTTON');
JText::script('COM_RSFIREWALL_CONFIRM_UNIGNORE');
JText::script('COM_RSFIREWALL_FILES_READDED_TO_CHECK');

JText::script('COM_RSFIREWALL_FOLDER_PERMISSIONS_INCORRECT');
JText::script('COM_RSFIREWALL_FOLDER_PERMISSIONS_CORRECT');
JText::script('COM_RSFIREWALL_PLEASE_WAIT_WHILE_BUILDING_DIRECTORY_STRUCTURE');
JText::script('COM_RSFIREWALL_FIX_FOLDER_PERMISSIONS_DONE');

JText::script('COM_RSFIREWALL_FILE_PERMISSIONS_INCORRECT');
JText::script('COM_RSFIREWALL_FILE_PERMISSIONS_CORRECT');
JText::script('COM_RSFIREWALL_PLEASE_WAIT_WHILE_BUILDING_FILE_STRUCTURE');
JText::script('COM_RSFIREWALL_FIX_FILE_PERMISSIONS_DONE');

JText::script('COM_RSFIREWALL_ITEMS_LEFT');

JText::script('COM_RSFIREWALL_MALWARE_PLEASE_REVIEW_FILES');
JText::script('COM_RSFIREWALL_VIEW_FILE');
JText::script('COM_RSFIREWALL_NO_MALWARE_FOUND');

JText::script('COM_RSFIREWALL_GRADE_NOT_FINISHED');
JText::script('COM_RSFIREWALL_GRADE_NOT_FINISHED_DESC');
JText::script('COM_RSFIREWALL_SCANNING_IN_PROGRESS_LEAVE');
JText::script('COM_RSFIREWALL_SURE_CHANGE_SESSION_HANDLER');

JHtml::_('script', 'com_rsfirewall/jquery.knob.js', array('relative' => true, 'version' => 'auto'));

$this->document->addScriptDeclaration(
	'RSFirewall.requestTimeOut.Seconds = \'' . (float) $this->config->get('request_timeout') . '\';' . "\n" .
	'RSFirewall.MaxRetries = ' . (int) $this->config->get('max_retries') . ';' . "\n" .
	'RSFirewall.RetriesTimeout = ' . (int) $this->config->get('retries_timeout') . ';'
);
?>
<form action="<?php echo JRoute::_('index.php?option=com_rsfirewall&view=check');?>" method="post" name="adminForm" id="adminForm">

<?php echo RSFirewallAdapterGrid::sidebar(); ?>
	<p class="alert alert-info" id="com-rsfirewall-last-run"><?php echo JText::sprintf('COM_RSFIREWALL_SYSTME_CHECK_LAST_RUN', $this->lastRun); ?></p>
	<div id="com-rsfirewall-main-content">
		<div id="com-rsfirewall-grade" style="display: none;">
			<h2><?php echo JText::_('COM_RSFIREWALL_GRADE_FINISHED'); ?></h2>
			<p><?php echo JText::_('COM_RSFIREWALL_GRADE_FINISHED_DESC'); ?></p>
			<input type="text" value="100" readonly="readonly" disabled="disabled" style="height: auto;" />
		</div>
		<?php if ($this->hasXdebug) { ?>
			<div class="alert alert-danger" id="com-rsfirewall-xdebug-warning">
			<p><strong><?php echo JText::_('COM_RSFIREWALL_SYSTEM_CHECK_HAS_DEBUG'); ?></strong></p>
			<p><?php echo JText::_('COM_RSFIREWALL_SYSTEM_CHECK_HAS_DEBUG_DESC'); ?></p>
			<p><button class="btn btn-danger btn-lg btn-large" type="button" onclick="document.getElementById('com-rsfirewall-start-button').removeAttribute('disabled'); document.getElementById('com-rsfirewall-xdebug-warning').style.display = 'none';"><?php echo JText::_('COM_RSFIREWALL_XDEBUG_I_UNDERSTAND_THE_RISKS_AND_WANT_TO_CONTINUE'); ?></button></p>
			</div>
		<?php } ?>
		<div id="com-rsfirewall-scan-in-progress" class="com-rsfirewall-hidden">
			<div class="lds-ripple"><div></div><div></div></div>
			<p><?php echo JText::_('COM_RSFIREWALL_SCAN_IS_IN_PROGRESS'); ?></p>
		</div>
		<p><button type="button" class="btn btn-primary" id="com-rsfirewall-start-button" <?php if ($this->hasXdebug) { ?>disabled="disabled"<?php } ?> onclick="RSFirewallStartCheck();"><?php echo JText::_('COM_RSFIREWALL_CHECK_SYSTEM'); ?></button></p>
		<div class="com-rsfirewall-content-box">
			<div class="com-rsfirewall-content-box-header">
				<h3><span class="icon-joomla"></span> <?php echo JText::_('COM_RSFIREWALL_JOOMLA_CONFIGURATION'); ?></h3>
			</div>
			<div id="com-rsfirewall-joomla-configuration" class="com-rsfirewall-content-box-content com-rsfirewall-hidden">
				<div class="com-rsfirewall-progress" id="com-rsfirewall-joomla-configuration-progress"><div class="com-rsfirewall-bar" style="width: 0%;">0%</div></div>
				<table id="com-rsfirewall-joomla-configuration-table">
					<thead>
						<tr>
						   <th><?php echo JText::_('COM_RSFIREWALL_ACTION'); ?></th>
						   <th><?php echo JText::_('COM_RSFIREWALL_RESULT'); ?></th>
						   <th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<tr class="com-rsfirewall-table-row com-rsfirewall-hidden">
							<td><span><?php echo JText::_('COM_RSFIREWALL_JOOMLA_VERSION_CHECK'); ?></span></td>
							<td class="com-rsfirewall-count"><span></span></td>
							<td nowrap="nowrap" width="1%"><button class="com-rsfirewall-button com-rsfirewall-details-button com-rsfirewall-hidden" type="button"><span class="icon-arrow-down"></span></button></td>
						</tr>
						<tr class="com-rsfirewall-table-row com-rsfirewall-hidden">
							<td colspan="3"></td>
						</tr>
						<tr class="com-rsfirewall-table-row alt-row com-rsfirewall-hidden">
							<td><span><?php echo JText::_('COM_RSFIREWALL_FIREWALL_VERSION_CHECK'); ?></span></td>
							<td class="com-rsfirewall-count"><span></span></td>
							<td nowrap="nowrap" width="1%"><button class="com-rsfirewall-button com-rsfirewall-details-button com-rsfirewall-hidden" type="button"><span class="icon-arrow-down"></span></button></td>
						</tr>
						<tr class="com-rsfirewall-table-row alt-row com-rsfirewall-hidden">
							<td colspan="3"></td>
						</tr>
						<tr class="com-rsfirewall-table-row com-rsfirewall-hidden">
							<td><span><?php echo JText::_('COM_RSFIREWALL_CHECKING_SQL_PASSWORD'); ?></span></td>
							<td class="com-rsfirewall-count"><span></span></td>
							<td nowrap="nowrap" width="1%"><button class="com-rsfirewall-button com-rsfirewall-details-button com-rsfirewall-hidden" type="button"><span class="icon-arrow-down"></span></button></td>
						</tr>
						<tr class="com-rsfirewall-table-row com-rsfirewall-hidden">
							<td colspan="3"></td>
						</tr>
						<tr class="com-rsfirewall-table-row alt-row com-rsfirewall-hidden">
							<td><span><?php echo JText::_('COM_RSFIREWALL_CHECKING_ADMIN_USER'); ?></span></td>
							<td class="com-rsfirewall-count"><span></span></td>
							<td nowrap="nowrap" width="1%"><button class="com-rsfirewall-button com-rsfirewall-details-button com-rsfirewall-hidden" type="button"><span class="icon-arrow-down"></span></button></td>
						</tr>
						<tr class="com-rsfirewall-table-row alt-row com-rsfirewall-hidden">
							<td colspan="3"><p><label for="com-rsfirewall-new-username"><?php echo JText::_('COM_RSFIREWALL_NEW_USERNAME'); ?></label> <input onkeyup="RSFirewallChangeAdminUserButtonText();" id="com-rsfirewall-new-username" type="text" name="" value="admin" /> <button type="button" onclick="RSFirewallFix('fixAdminUser', this)" id="com-rsfirewall-rename-admin-button" class="com-rsfirewall-button com-rsfirewall-fix-button"><?php echo JText::sprintf('COM_RSFIREWALL_RENAME_ADMIN', 'admin'); ?></button></p></td>
						</tr>
						<tr class="com-rsfirewall-table-row com-rsfirewall-hidden">
							<td><span><?php echo JText::_('COM_RSFIREWALL_CHECKING_FTP_PASSWORD'); ?></span></td>
							<td class="com-rsfirewall-count"><span></span></td>
							<td nowrap="nowrap" width="1%"><button class="com-rsfirewall-button com-rsfirewall-details-button com-rsfirewall-hidden" type="button"><span class="icon-arrow-down"></span></button></td>
						</tr>
						<tr class="com-rsfirewall-table-row com-rsfirewall-hidden">
							<td colspan="3"><p><button type="button" onclick="RSFirewallFix('fixFTPPassword', this)" class="com-rsfirewall-button com-rsfirewall-fix-button"><?php echo JText::_('COM_RSFIREWALL_REMOVE_FTP_PASSWORD'); ?></button></p></td>
						</tr>
						<tr class="com-rsfirewall-table-row alt-row com-rsfirewall-hidden">
							<td><span><?php echo JText::_('COM_RSFIREWALL_CHECKING_SEF'); ?></span></td>
							<td class="com-rsfirewall-count"><span></span></td>
							<td nowrap="nowrap" width="1%"><button class="com-rsfirewall-button com-rsfirewall-details-button com-rsfirewall-hidden" type="button"><span class="icon-arrow-down"></span></button></td>
						</tr>
						<tr class="com-rsfirewall-table-row alt-row com-rsfirewall-hidden">
							<td colspan="3"><p><button type="button" onclick="RSFirewallFix('fixSEF', this)" class="com-rsfirewall-button com-rsfirewall-fix-button"><?php echo JText::_('COM_RSFIREWALL_ENABLE_SEF'); ?></button></p></td>
						</tr>
						<tr class="com-rsfirewall-table-row com-rsfirewall-hidden">
							<td><span><?php echo JText::_('COM_RSFIREWALL_CHECKING_CONFIGURATION_INTEGRITY'); ?></span></td>
							<td class="com-rsfirewall-count"><span></span></td>
							<td nowrap="nowrap" width="1%"><button class="com-rsfirewall-button com-rsfirewall-details-button com-rsfirewall-hidden" type="button"><span class="icon-arrow-down"></span></button></td>
						</tr>
						<tr class="com-rsfirewall-table-row com-rsfirewall-hidden">
							<td colspan="3"><p><button type="button" onclick="RSFirewallFix('fixConfiguration', this)" class="com-rsfirewall-button com-rsfirewall-fix-button"><?php echo JText::_('COM_RSFIREWALL_REBUILD_CONFIGURATION'); ?></button></p><p><?php echo JText::_('COM_RSFIREWALL_CONFIGURATION_DETAILS'); ?></p></td>
						</tr>
						<tr class="com-rsfirewall-table-row alt-row com-rsfirewall-hidden">
							<td><span><?php echo JText::_('COM_RSFIREWALL_CHECKING_SESSION_LIFETIME'); ?></span></td>
							<td class="com-rsfirewall-count"><span></span></td>
							<td nowrap="nowrap" width="1%"><button class="com-rsfirewall-button com-rsfirewall-details-button com-rsfirewall-hidden" type="button"><span class="icon-arrow-down"></span></button></td>
						</tr>
						<tr class="com-rsfirewall-table-row alt-row com-rsfirewall-hidden">
							<td colspan="3"><p><button type="button" onclick="RSFirewallFix('fixSession', this)" class="com-rsfirewall-button com-rsfirewall-fix-button"><?php echo JText::_('COM_RSFIREWALL_DECREASE_SESSION'); ?></button></p></td>
						</tr>
						<tr class="com-rsfirewall-table-row com-rsfirewall-hidden">
							<td><span><?php echo JText::_('COM_RSFIREWALL_CHECKING_TEMPORARY_FILES'); ?></span></td>
							<td class="com-rsfirewall-count"><span></span></td>
							<td nowrap="nowrap" width="1%"><button class="com-rsfirewall-button com-rsfirewall-details-button com-rsfirewall-hidden" type="button"><span class="icon-arrow-down"></span></button></td>
						</tr>
						<tr class="com-rsfirewall-table-row com-rsfirewall-hidden">
							<td colspan="3"><p><button type="button" onclick="RSFirewallFix('fixTemporaryFiles', this)" class="com-rsfirewall-button com-rsfirewall-fix-button"><?php echo JText::_('COM_RSFIREWALL_EMPTY_TEMPORARY_FOLDER'); ?></button></p></td>
						</tr>
						<tr class="com-rsfirewall-table-row alt-row com-rsfirewall-hidden">
							<td><span><?php echo JText::sprintf('COM_RSFIREWALL_CHECKING_HTACCESS', $this->accessFile); ?></span></td>
							<td class="com-rsfirewall-count"><span></span></td>
							<td nowrap="nowrap" width="1%"><button class="com-rsfirewall-button com-rsfirewall-details-button com-rsfirewall-hidden" type="button"><span class="icon-arrow-down"></span></button></td>
						</tr>
						<tr class="com-rsfirewall-table-row alt-row com-rsfirewall-hidden">
							<td colspan="3"><p><button type="button" onclick="RSFirewallFix('fixHtaccess', this)" class="com-rsfirewall-button com-rsfirewall-fix-button"><?php echo JText::sprintf('COM_RSFIREWALL_RENAME_HTACCESS', $this->defaultAccessFile, $this->accessFile); ?></button></p></td>
						</tr>
						<tr class="com-rsfirewall-table-row com-rsfirewall-hidden">
							<td><span><?php echo JText::_('COM_RSFIREWALL_CHECKING_SESSION_HANDLER'); ?></span></td>
							<td class="com-rsfirewall-count"><span></span></td>
							<td nowrap="nowrap" width="1%"><button class="com-rsfirewall-button com-rsfirewall-details-button com-rsfirewall-hidden" type="button"><span class="icon-arrow-down"></span></button></td>
						</tr>
						<tr class="com-rsfirewall-table-row com-rsfirewall-hidden">
							<td colspan="3"><p><button type="button" onclick="RSFirewallFix('fixSessionHandler', this)" class="com-rsfirewall-button com-rsfirewall-fix-button"><?php echo JText::_('COM_RSFIREWALL_FIX_SESSION_HANDLER'); ?></button></p></td>
						</tr>
						<tr class="com-rsfirewall-table-row alt-row com-rsfirewall-hidden">
							<td><span><?php echo JText::_('COM_RSFIREWALL_ADDITIONAL_BACKEND_PASSWORD_ENABLED'); ?></span></td>
							<td class="com-rsfirewall-count"><span></span></td>
							<td nowrap="nowrap" width="1%"><button class="com-rsfirewall-button com-rsfirewall-details-button com-rsfirewall-hidden" type="button"><span class="icon-arrow-down"></span></button></td>
						</tr>
						<tr class="com-rsfirewall-table-row alt-row com-rsfirewall-hidden">
							<td colspan="3"></td>
						</tr>
						<?php if (in_array('safebrowsing', $this->config->get('google_apis'))) { ?>
							<tr class="com-rsfirewall-table-row com-rsfirewall-hidden">
								<td><span><?php echo JText::_('COM_RSFIREWALL_CHECKING_GOOGLE_SAFE_BROWSER'); ?></span></td>
								<td class="com-rsfirewall-count"><span></span></td>
								<td nowrap="nowrap" width="1%"><button class="com-rsfirewall-button com-rsfirewall-details-button com-rsfirewall-hidden" type="button"><span class="icon-arrow-down"></span></button></td>
							</tr>
							<tr class="com-rsfirewall-table-row com-rsfirewall-hidden">
								<td colspan="3"></td>
							</tr>
						<?php } ?>
						<?php if (in_array('webrisk', $this->config->get('google_apis'))) { ?>
							<tr class="com-rsfirewall-table-row <?php if (count($this->config->get('google_apis')) == 2) { echo 'alt-row'; } ?> com-rsfirewall-hidden">
								<td><span><?php echo JText::_('COM_RSFIREWALL_CHECKING_GOOGLE_WEB_RISK'); ?></span></td>
								<td class="com-rsfirewall-count"><span></span></td>
								<td nowrap="nowrap" width="1%"><button class="com-rsfirewall-button com-rsfirewall-details-button com-rsfirewall-hidden" type="button"><span class="icon-arrow-down"></span></button></td>
							</tr>
							<tr class="com-rsfirewall-table-row <?php if (count($this->config->get('google_apis')) == 2) { echo 'alt-row'; } ?> com-rsfirewall-hidden">
								<td colspan="3"></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div><!-- Joomla! config -->
		<div class="com-rsfirewall-content-box">
			<div class="com-rsfirewall-content-box-header">
				<h3><span class="icon-database"></span> <?php echo JText::_('COM_RSFIREWALL_SERVER_CONFIGURATION'); ?></h3>
			</div>
			<div id="com-rsfirewall-server-configuration" class="com-rsfirewall-content-box-content com-rsfirewall-hidden">
				<div class="com-rsfirewall-progress" id="com-rsfirewall-server-configuration-progress"><div class="com-rsfirewall-bar" style="width: 0%;">0%</div></div>
				<table id="com-rsfirewall-server-configuration-table">
					<thead>
						<tr>
						   <th><?php echo JText::_('COM_RSFIREWALL_PHP_DIRECTIVE'); ?></th>
						   <th><?php echo JText::_('COM_RSFIREWALL_RESULT'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php if (!$this->isPHP54) { ?>
						<tr class="com-rsfirewall-table-row com-rsfirewall-hidden">
							<td width="15%" nowrap="nowrap"><span>register_globals</span></td>
							<td class="com-rsfirewall-count"><span></span></td>
						</tr>
						<tr class="com-rsfirewall-table-row alt-row com-rsfirewall-hidden">
							<td colspan="2"></td>
						</tr>
						<?php } ?>
						<tr class="com-rsfirewall-table-row alt-row com-rsfirewall-hidden">
							<td width="15%" nowrap="nowrap"><span>allow_url_include</span></td>
							<td class="com-rsfirewall-count"><span></span></td>
						</tr>
						<tr class="com-rsfirewall-table-row alt-row com-rsfirewall-hidden">
							<td colspan="2"></td>
						</tr>
						<tr class="com-rsfirewall-table-row com-rsfirewall-hidden">
							<td width="15%" nowrap="nowrap"><span>open_basedir</span></td>
							<td class="com-rsfirewall-count"><span></span></td>
						</tr>
						<tr class="com-rsfirewall-table-row com-rsfirewall-hidden">
							<td colspan="2"></td>
						</tr>
						<tr class="com-rsfirewall-table-row alt-row com-rsfirewall-hidden">
							<td width="15%" nowrap="nowrap"><span>disable_functions</span></td>
							<td class="com-rsfirewall-count"><span></span></td>
						</tr>
						<tr class="com-rsfirewall-table-row alt-row com-rsfirewall-hidden">
							<td colspan="2"></td>
						</tr>
						<tr class="com-rsfirewall-table-row com-rsfirewall-hidden">
							<td width="15%" nowrap="nowrap"><span>expose_php</span></td>
							<td class="com-rsfirewall-count"><span></span></td>
						</tr>
						<tr class="com-rsfirewall-table-row com-rsfirewall-hidden">
							<td colspan="2"></td>
						</tr>
						<?php if (!$this->isPHP54) { ?>
						<tr class="com-rsfirewall-table-row alt-row com-rsfirewall-hidden">
							<td width="15%" nowrap="nowrap"><span>safe_mode</span></td>
							<td class="com-rsfirewall-count"><span></span></td>
						</tr>
						<tr class="com-rsfirewall-table-row alt-row com-rsfirewall-hidden">
							<td colspan="2"></td>
						</tr>
						<?php } ?>
						<tr class="com-rsfirewall-table-row com-rsfirewall-hidden" id="com-rsfirewall-server-configuration-fix">
							<td colspan="2">
								<p><button type="button" onclick="RSFirewallFix('fixPHP', this)" class="com-rsfirewall-button com-rsfirewall-fix-button"><?php echo JText::_('COM_RSFIREWALL_FIX_PHP'); ?></button></p>
								<p class="alert"><small><?php echo JText::_('COM_RSFIREWALL_PHP_ERROR_DESC'); ?></small></p>
								<div id="com-rsfirewall-php-ini-wrapper" class="com-rsfirewall-hidden">
									<p><?php echo JText::_('COM_RSFIREWALL_PHP_INI_INSTRUCTIONS'); ?></p>
									<pre id="com-rsfirewall-php-ini"></pre>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div><!-- Server config -->
		<div class="com-rsfirewall-content-box">
			<div class="com-rsfirewall-content-box-header">
				<h3><span class="icon-file"></span> <?php echo JText::_('COM_RSFIREWALL_SCAN_RESULT'); ?></h3>
			</div>
			<div id="com-rsfirewall-file-scan" class="com-rsfirewall-content-box-content com-rsfirewall-hidden">
				<table id="com-rsfirewall-file-scan-table">
					<thead>
						<tr>
						   <th><?php echo JText::_('COM_RSFIREWALL_ACTION'); ?></th>
						   <th><?php echo JText::_('COM_RSFIREWALL_RESULT'); ?></th>
						   <th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<tr class="com-rsfirewall-table-row alt-row com-rsfirewall-hidden">
							<td width="15%" nowrap="nowrap"><span><?php echo JText::_('COM_RSFIREWALL_SCANNING_JOOMLA_HASHES'); ?></span></td>
							<td class="com-rsfirewall-count"><span></span> <button type="button" href="index.php?option=com_rsfirewall&view=ignored&tmpl=component" onclick="window.open(jQuery(this).attr('href'), 'ignoreWindow', 'width=800, height=600, left=50%, location=0, menubar=0, resizable=1, scrollbars=1, toolbar=0, titlebar=1')" class="com-rsfirewall-button com-rsfirewall-hidden" id="com-rsfirewall-ignore-files-button"><?php echo JText::_('COM_RSFIREWALL_VIEW_IGNORED'); ?></button></td>
							<td nowrap="nowrap" width="1%"><button class="com-rsfirewall-button com-rsfirewall-details-button com-rsfirewall-hidden" type="button"><span class="icon-arrow-down"></span></button></td>
						</tr>
						<tr class="com-rsfirewall-table-row alt-row com-rsfirewall-hidden">
							<td colspan="3">
								<p><button type="button" onclick="RSFirewallFix('fixHashes', this)" class="com-rsfirewall-button com-rsfirewall-fix-button"><?php echo JText::_('COM_RSFIREWALL_ACCEPT_CHANGES'); ?></button></p>
								<p class="alert"><small><?php echo JText::_('COM_RSFIREWALL_ACCEPT_CHANGES_WARNING'); ?></small></p>
							</td>
						</tr>
						<?php if (!$this->isWindows) { ?>
						<tr class="com-rsfirewall-table-row com-rsfirewall-hidden">
							<td width="15%" nowrap="nowrap"><span><?php echo JText::_('COM_RSFIREWALL_SCANNING_FOLDERS'); ?></span></td>
							<td class="com-rsfirewall-count"><span></span></td>
							<td nowrap="nowrap" width="1%"><button class="com-rsfirewall-button com-rsfirewall-details-button com-rsfirewall-hidden" type="button"><span class="icon-arrow-down"></span></button></td>
						</tr>
						<tr class="com-rsfirewall-table-row com-rsfirewall-hidden">
							<td colspan="3">
								<p><button type="button" onclick="RSFirewallFix('fixFolderPermissions', this)" class="com-rsfirewall-button com-rsfirewall-fix-button"><?php echo JText::sprintf('COM_RSFIREWALL_ATTEMPT_TO_FIX_FOLDER_PERMISSIONS', $this->config->get('folder_permissions')); ?></button></p>
								<p class="alert"><small><?php echo JText::sprintf('COM_RSFIREWALL_FIX_FOLDER_PERMISSIONS_WARNING', $this->config->get('folder_permissions')); ?></small></p>
							</td>
						</tr>
						<tr class="com-rsfirewall-table-row alt-row com-rsfirewall-hidden">
							<td width="15%" nowrap="nowrap"><span><?php echo JText::_('COM_RSFIREWALL_SCANNING_FILES'); ?></span></td>
							<td class="com-rsfirewall-count"><span></span></td>
							<td nowrap="nowrap" width="1%"><button class="com-rsfirewall-button com-rsfirewall-details-button com-rsfirewall-hidden" type="button"><span class="icon-arrow-down"></span></button></td>
						</tr>
						<tr class="com-rsfirewall-table-row alt-row com-rsfirewall-hidden">
							<td colspan="3">
								<p><button type="button" onclick="RSFirewallFix('fixFilePermissions', this)" class="com-rsfirewall-button com-rsfirewall-fix-button"><?php echo JText::sprintf('COM_RSFIREWALL_ATTEMPT_TO_FIX_FILE_PERMISSIONS', $this->config->get('file_permissions')); ?></button></p>
								<p class="alert"><small><?php echo JText::sprintf('COM_RSFIREWALL_FIX_FILE_PERMISSIONS_WARNING', $this->config->get('file_permissions')); ?></small></p>
							</td>
						</tr>
						<?php } ?>
						<tr class="com-rsfirewall-table-row com-rsfirewall-hidden">
							<td width="15%" nowrap="nowrap"><span><?php echo JText::_('COM_RSFIREWALL_SCANNING_FILES_FOR_MALWARE'); ?></span></td>
							<td class="com-rsfirewall-count"><span></span></td>
							<td nowrap="nowrap" width="1%"><button class="com-rsfirewall-button com-rsfirewall-details-button com-rsfirewall-hidden" type="button"><span class="icon-arrow-down"></span></button></td>
						</tr>
						<tr class="com-rsfirewall-table-row alt-row com-rsfirewall-hidden">
							<td colspan="3">
								<p><button type="button" onclick="RSFirewallFix('ignoreFiles', this)" class="com-rsfirewall-button com-rsfirewall-fix-button"><?php echo JText::_('COM_RSFIREWALL_IGNORE_FILES'); ?></button></p>
								<p class="alert"><small><?php echo JText::_('COM_RSFIREWALL_IGNORE_FILES_WARNING'); ?></small></p>
							</td>
						</tr>
						<tr class="com-rsfirewall-table-row com-rsfirewall-hidden">
							<td colspan="3"></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div><!-- Scan result -->
	</div>
</div>
</form>

<script type="text/javascript">
function RSFirewallStartCheck() {
    jQuery('#com-rsfirewall-last-run').remove();
	jQuery('#com-rsfirewall-start-button').remove();
	RSFirewall.System.Check.unhide('#com-rsfirewall-scan-in-progress').hide().fadeIn('slow');

	// Joomla! Configuration Check
	RSFirewall.System.Check.prefix = 'com-rsfirewall-joomla-configuration';

	RSFirewall.System.Check.steps = <?php echo json_encode($this->getSystemCheckSteps()); ?>;

	RSFirewall.System.Check.stopCheck = function() {
		jQuery('#com-rsfirewall-joomla-configuration-progress').fadeOut('fast', function(){jQuery(this).remove()});
		RSFirewallServerCheck();
	};
	RSFirewall.System.Check.startCheck();
}

function RSFirewallServerCheck() {
	// Server Configuration Check
	RSFirewall.System.Check.prefix = 'com-rsfirewall-server-configuration';
	RSFirewall.System.Check.steps = <?php echo json_encode($this->getServerCheckSteps()); ?>;
	RSFirewall.System.Check.stopCheck = function() {
		jQuery('#com-rsfirewall-server-configuration-progress').fadeOut('fast', function(){jQuery(this).remove()});
		RSFirewallFilesCheck();
	};
	RSFirewall.System.Check.startCheck();
}

function RSFirewallFilesCheck() {
	RSFirewall.System.Check.prefix = 'com-rsfirewall-file-scan';
	RSFirewall.System.Check.steps = <?php echo json_encode($this->getFilesCheckSteps()); ?>;
	RSFirewall.System.Check.stopCheck = function() {
		jQuery('#com-rsfirewall-scan-in-progress').remove();

		RSFirewall.Grade.create();
		jQuery(window).scrollTop(0);
	};
	RSFirewall.System.Check.startCheck();
}

function RSFirewallChangeAdminUserButtonText() {
	var text 	= jQuery('#com-rsfirewall-new-username').val();
	var button 	= jQuery('#com-rsfirewall-rename-admin-button');

	button.text(Joomla.JText._('COM_RSFIREWALL_RENAME_ADMIN').replace('%s', text));
}

function RSFirewallFix(step, button) {
	RSFirewall.System.Check.limit = <?php echo $this->offset; ?>;

	// disable the checkboxes
	if (step === 'fixFolderPermissions') {
		jQuery('input[name="folders[]"]').prop('disabled', true);
	} else if (step === 'fixFilePermissions') {
		jQuery('input[name="files[]"]').prop('disabled', true);
	} else if (step === 'fixHashes') {
		jQuery('input[name="hashes[]"]').prop('disabled', true);
	} else if (step === 'ignoreFiles') {
		jQuery('input[name="ignorefiles[]"]').prop('disabled', true);
	}

	if (step === 'fixSessionHandler' && !confirm(Joomla.JText._('COM_RSFIREWALL_SURE_CHANGE_SESSION_HANDLER'))) {
		return;
	}

	RSFirewall.System.Check.fix(step, button);
}

window.onbeforeunload = function() {
    try {
        if (!document.getElementById('com-rsfirewall-scan-in-progress').classList.contains('com-rsfirewall-hidden')) {
            return Joomla.JText._('COM_RSFIREWALL_SCANNING_IN_PROGRESS_LEAVE');
        }
    } catch (err) {}
}
</script>