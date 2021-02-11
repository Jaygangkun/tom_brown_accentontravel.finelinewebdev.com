<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2020 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>
<form action="<?php echo JRoute::_('index.php?option=com_rsfirewall&view=exceptions'); ?>" method="post" name="adminForm" id="adminForm">		

	<?php echo RSFirewallAdapterGrid::sidebar(); ?>
	<?php
	echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));

	if (empty($this->items)) { ?>
		<div class="alert alert-info">
			<span class="fa fa-info-circle" aria-hidden="true"></span><span class="sr-only"><?php echo JText::_('INFO'); ?></span>
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
	<?php } else { ?>
		<table class="table table-striped">
			<caption id="captionTable" class="sr-only">
				<?php echo JText::_('COM_RSFIREWALL_EXCEPTIONS_TABLE_CAPTION'); ?>,
				<span id="orderedBy"><?php echo JText::_('JGLOBAL_SORTED_BY'); ?> </span>,
				<span id="filteredBy"><?php echo JText::_('JGLOBAL_FILTERED_BY'); ?></span>
			</caption>
			<thead>
			<tr>
				<th style="width:1%" class="text-center">
					<?php echo JHtml::_('grid.checkall'); ?>
				</th>
				<th width="1%" nowrap="nowrap"><?php echo JHtml::_('searchtools.sort', 'COM_RSFIREWALL_EXCEPTION_DATE', 'date', $listDirn, $listOrder); ?></th>
				<th><?php echo JHtml::_('searchtools.sort', 'COM_RSFIREWALL_EXCEPTION_MATCH', 'match', $listDirn, $listOrder); ?></th>
				<th><?php echo JHtml::_('searchtools.sort', 'COM_RSFIREWALL_EXCEPTION_REASON', 'reason', $listDirn, $listOrder); ?></th>
				<th width="1%" nowrap="nowrap"><?php echo JHtml::_('searchtools.sort', 'COM_RSFIREWALL_EXCEPTION_TYPE', 'type', $listDirn, $listOrder); ?></th>
				<th width="1%" nowrap="nowrap"><?php echo JHtml::_('searchtools.sort', 'JPUBLISHED', 'published', $listDirn, $listOrder); ?></th>
			</tr>
			</thead>
		<?php foreach ($this->items as $i => $item) { ?>
			<tr>
				<td style="width:1%" class="text-center"><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
				<td width="1%" nowrap="nowrap"><?php echo JHtml::_('date', $item->date); ?></td>
				<td><a href="<?php echo JRoute::_('index.php?option=com_rsfirewall&task=exception.edit&id='.(int) $item->id); ?>"><?php echo $this->escape($item->match); ?></a></td>
				<td><?php echo $this->escape($item->reason); ?></td>
				<td width="1%" nowrap="nowrap" class="com-rsfirewall-exception-type-<?php echo $item->type; ?>"><?php echo JText::_('COM_RSFIREWALL_EXCEPTION_TYPE_'.$item->type); ?></td>
				<td width="1%" nowrap="nowrap" align="center"><?php echo JHtml::_('jgrid.published', $item->published, $i, 'exceptions.'); ?></td>
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