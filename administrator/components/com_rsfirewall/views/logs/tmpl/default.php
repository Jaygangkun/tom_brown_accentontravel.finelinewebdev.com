<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2020 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

$this->document->addScriptDeclaration('RSFirewall.Status.errorContainer = jQuery(\'.com-rsfirewall-log-message\');');

JText::script('COM_RSFIREWALL_BLOCK');
JText::script('COM_RSFIREWALL_UNBLOCK');
JText::script('COM_RSFIREWALL_LOG_ERROR');
JText::script('COM_RSFIREWALL_LOG_WARNING');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>
<form action="<?php echo JRoute::_('index.php?option=com_rsfirewall&view=logs'); ?>" method="post" name="adminForm" id="adminForm">

	<?php echo RSFirewallAdapterGrid::sidebar(); ?>
	<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
	<div class="com-rsfirewall-log-message"></div>
	<?php
	if (empty($this->items)) { ?>
		<div class="alert alert-info">
			<span class="fa fa-info-circle" aria-hidden="true"></span><span class="sr-only"><?php echo JText::_('INFO'); ?></span>
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
	<?php } else { ?>
	<table class="table table-striped">
		<caption id="captionTable" class="sr-only">
			<?php echo JText::_('COM_RSFIREWALL_LOGS_TABLE_CAPTION'); ?>,
			<span id="orderedBy"><?php echo JText::_('JGLOBAL_SORTED_BY'); ?> </span>,
			<span id="filteredBy"><?php echo JText::_('JGLOBAL_FILTERED_BY'); ?></span>
		</caption>
		<thead>
		<tr>
			<th style="width:1%" class="text-center">
				<?php echo JHtml::_('grid.checkall'); ?>
			</th>
			<th width="1%" nowrap="nowrap">

			</th>
			<th width="1%" nowrap="nowrap" class="hidden-phone">
				<?php echo JHtml::_('searchtools.sort', 'COM_RSFIREWALL_ALERT_LEVEL', 'logs.level', $listDirn, $listOrder); ?>
			</th>
			<th width="1%" nowrap="nowrap" class="hidden-phone">
				<?php echo JHtml::_('searchtools.sort', 'COM_RSFIREWALL_LOG_DATE_EVENT', 'logs.date', $listDirn, $listOrder); ?>
			</th>
			<th scope="col" style="min-width:100px">
				<?php echo JHtml::_('searchtools.sort', 'COM_RSFIREWALL_LOG_IP_ADDRESS', 'logs.ip', $listDirn, $listOrder); ?>
			</th>
			<th width="1%" nowrap="nowrap" class="hidden-phone">
				<?php echo JHtml::_('searchtools.sort', 'COM_RSFIREWALL_LOG_USER_ID', 'logs.user_id', $listDirn, $listOrder); ?>
			</th>
			<th width="1%" nowrap="nowrap" class="hidden-phone">
				<?php echo JHtml::_('searchtools.sort', 'COM_RSFIREWALL_LOG_USERNAME', 'logs.username', $listDirn, $listOrder); ?>
			</th>
			<th>
				<?php echo JHtml::_('searchtools.sort', 'COM_RSFIREWALL_LOG_PAGE', 'logs.page', $listDirn, $listOrder); ?>
			</th>
			<th class="hidden-phone">
				<?php echo JHtml::_('searchtools.sort', 'COM_RSFIREWALL_LOG_REFERER', 'logs.referer', $listDirn, $listOrder); ?>
			</th>
			<th>
				<?php echo JText::_('COM_RSFIREWALL_LOG_DESCRIPTION'); ?>
			</th>
		</tr>
		</thead>
	<?php foreach ($this->items as $i => $item) { ?>
		<tr class="rsf-entry" id="rsf-log-<?php echo $item->id;?>">
			<td style="width:1%" class="text-center">
				<?php echo JHtml::_('grid.id', $i, $item->id); ?>
			</td>
			<td width="1%" nowrap="nowrap" class="rsf-status"><?php 
				if (!is_null($item->type))
				{
					echo $item->type ? JText::_('COM_RSFIREWALL_WHITELISTED') : '<button type="button" onclick="RSFirewall.Status.Change('.$item->id.','.$item->listId.', \'unblockajax\', this)" class="btn btn-small btn-sm btn-secondary">'.JText::_('COM_RSFIREWALL_UNBLOCK').'</button>';
				}
				else
				{
					echo '<button type="button" onclick="RSFirewall.Status.Change(' . $item->id . ', null, \'blockajax\', this);" class="btn btn-danger btn-small btn-sm">' . JText::_('COM_RSFIREWALL_BLOCK') . '</button>';
				}
			?></td>
			<td width="1%" nowrap="nowrap" class="hidden-phone com-rsfirewall-level-<?php echo $item->level; ?>"><?php echo JText::_('COM_RSFIREWALL_LEVEL_'.$item->level); ?></td>
			<td width="1%" nowrap="nowrap" class="hidden-phone"><?php echo $this->showDate($item->date); ?></td>
			<td width="1%" nowrap="nowrap"><?php echo JHtml::_('image', 'com_rsfirewall/flags/' . $this->geoip->getCountryFlag($item->ip), $this->geoip->getCountryCode($item->ip), array(), true); ?> <?php echo $this->geoip->show($item->ip); ?></td>
			<td width="1%" nowrap="nowrap" class="hidden-phone"><?php echo (int) $item->user_id; ?></td>
			<td width="1%" nowrap="nowrap" class="hidden-phone"><?php echo $this->escape($item->username); ?></td>
			<td class="com-rsfirewall-break-word"><?php echo $this->escape($item->page); ?></td>
			<td class="hidden-phone com-rsfirewall-break-word"><?php echo $item->referer ? $this->escape($item->referer) : '<em>'.JText::_('COM_RSFIREWALL_NO_REFERER').'</em>'; ?></td>
			<td class="com-rsfirewall-break-word">
				<?php echo JText::_('COM_RSFIREWALL_EVENT_'.$item->code); ?>
				<?php if (!empty($item->debug_variables)) { ?>
					<button type="button" class="btn btn-small btn-info btn-sm" onclick="jQuery(this).parent().find('.com-rsfirewall-hidden').removeClass('com-rsfirewall-hidden'); jQuery(this).remove();"><?php echo JText::_('COM_RSFIREWALL_SHOW'); ?></button>
					<div class="com-rsfirewall-hidden">
						<p><b><?php echo JText::_('COM_RSFIREWALL_LOG_DEBUG_VARIABLES'); ?></b></p>
						<?php echo nl2br($this->escape($item->debug_variables)); ?>
					</div>
				<?php } ?>
			</td>
		</tr>
	<?php } ?>
	</table>
		<?php echo $this->pagination->getListFooter(); ?>
	<?php } ?>
	
	<div>
		<?php echo JHtml::_( 'form.token' ); ?>
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="task" value="" />
	</div>
	</div>
</form>