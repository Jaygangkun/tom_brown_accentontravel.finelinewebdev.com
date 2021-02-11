<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class FLICViewCategory
{
	function setCategoryToolbar()
	{
		JToolBarHelper::title( JText::_( 'FLIC Manager - Categories' ), 'generic.png' );
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::deleteList();
		JToolBarHelper::editList();
		JToolBarHelper::addNew('add');
		JToolBarHelper::preferences('com_flic', '200');
	}

	function categories( &$rows, &$pageNav, &$lists )
	{
		FLICViewCategory::setCategoryToolbar();
		$user =& JFactory::getUser();
		JHTML::_('behavior.tooltip');
		?>
		<form action="index.php?option=com_flic&view=categories" class="form-inline" method="post" name="adminForm" id="adminForm">
		<table>
		<tr>
			<td align="left" width="100%">&nbsp;</td>
			<td nowrap="nowrap">
				<?php
				echo $lists['state'];
				?>

			</td>
		</tr>
		</table>

			<table class="adminlist table table-striped">
			<thead>
				<tr>
					<th width="20">
						<?php echo JText::_( 'Num' ); ?>
					</th>
					<th width="20">
						<input type="checkbox" name="toggle" value=""  onclick="checkAll(<?php echo count( $rows ); ?>);" />
					</th>
					<th nowrap="nowrap" class="title">
						<?php echo JHTML::_('grid.sort',   'Name', 'p.name', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="5%" nowrap="nowrap"> 
						<?php echo JHTML::_('grid.sort',   'Published', 'p.showCategory', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="1%" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort',   'ID', 'p.flic_category_id', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
				</tr>
			</thead> 
			<tfoot>
				<tr>
					<td colspan="13">
						<?php echo $pageNav->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php
			$k = 0;
			$ordering   = (@$lists['order'] == 'p.ordering');
			for ($i=0, $n=count( $rows ); $i < $n; $i++) {
				$row = &$rows[$i];

				$row->id	= $row->flic_category_id;
				$link		= JRoute::_( 'index.php?option=com_flic&view=categories&task=edit&flic_category_id[]='. $row->flic_category_id );

				$row->published = $row->showCategory;
				$published		= JHTML::_('grid.published', $row, $i );
				$checked		= JHTML::_('grid.id', $i, $row->id , '', 'flic_category_id');
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center">
						<?php echo $pageNav->getRowOffset($i); ?>
					</td>
					<td align="center">
						<?php echo $checked; ?>
					</td>
					<td>
					<span class="editlinktip hasTip" title="Edit:: <?php echo $row->name; ?>">
						<?php
						$indent = "";
						for($x = 0; $x < $row->treeLevel; $x++)
							$indent .= "&nbsp;|---- ";
						if ( JTable::isCheckedOut($user->get ('id'), $row->checked_out ) ) {
							echo $indent. $row->name;
						} else {
							?>

							<a href="<?php echo $link; ?>">
								<?php 
								echo $indent . $row->name;
								?>
								
							</a>
							<?php
						}
						?>
						</span>
					</td>
					<td align="center">
						<?php echo $published;?>
					</td>
					<td align="center">
						<?php echo $row->flic_category_id; ?>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
			</tbody>
			</table>

		<input type="hidden" name="view" value="categories" />
		<input type="hidden" name="option" value="com_flic" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir']; ?>" />
		<?php echo JHTML::_( 'form.token' ); ?>
		</form>
		<?php
	}

	function setOneCategoryToolbar()
	{
		$task = JRequest::getVar( 'task', '', 'method', 'string');

		JToolBarHelper::title( $task == 'add' ? JText::_( 'Gallery' ) . ': <small><small>[ '. JText::_( 'New' ) .' ]</small></small>' : JText::_( 'Gallery' ) . ': <small><small>[ '. JText::_( 'Edit' ) .' ]</small></small>', 'generic.png' );
		JToolBarHelper::save( 'save' );
		JToolBarHelper::apply('apply');
		JToolBarHelper::cancel( 'cancel' );
	}

	function category( &$row, &$lists )
	{
        jimport('joomla.application.component.helper');
    	$params = JComponentHelper::getParams('com_flic');
		$editor=& JFactory::getEditor();
 
		FLICViewCategory::setOneCategoryToolbar();
		JRequest::setVar( 'hidemainmenu', 1 );

		?>
		<script language="javascript" type="text/javascript">
		<!--
	
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}
			// do field validation
			if (form.name.value == "") {
				alert( "<?php echo JText::_( 'You must provide a name.', true ); ?>" );
			} else {
				submitform( pressbutton );
			}
		}
		//-->
		</script>
		
		<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">

				<div class="row-fluid">
					<div class="span9">
						<fieldset class="adminform">
							<legend><?php echo JText::_( 'Details' ); ?></legend>
			
							<table class="adminlist table table-striped">
							<tbody>
								<tr>
									<td width="20%" class="key">
										<label for="name">
											<?php echo JText::_( 'Category Name' ); ?>:
										</label>
									</td>
									<td width="80%">
										<input class="inputbox" type="text" name="name" id="name" size="50" value="<?php echo $row->name;?>" />
									</td>
								</tr>
								<tr>
									<td width="20%" class="key">
										<label for="alias">
											<?php echo JText::_( 'Category Alias' ); ?>:
										</label>
									</td>
									<td width="80%">
										<input class="inputbox" type="text" name="alias" id="alias" size="50" value="<?php echo $row->alias;?>" />
									</td>
								</tr>
								<tr>
									<td class="key">
										<?php echo JText::_( 'Parent' ); ?>:
									</td>
									<td>
										<?php echo $lists['parents']; ?>
									</td>
								</tr>
								<tr>
									<td class="key">
										<?php echo JText::_( 'Published' ); ?>:
									</td>
									<td>
										<?php echo $lists['showCategory']; ?>
									</td>
								</tr>
								
								<tr>
									<td width="20%" class="key">
										<label for="description">
											<?php echo JText::_( 'Description' ); ?>:
										</label>
									</td>
									<td width="80%">
										<? echo $editor->display( 'description',  $row->description, '100%', '300', '75', '20' ) ; ?>
									</td>
								</tr>
								<tr>
									<td width="20%" class="key">
										<label for="shortDescription">
											<?php echo JText::_( 'Short Description' ); ?>:
										</label>
									</td>
									<td width="80%">
										<? echo $editor->display( 'shortDescription',  $row->shortDescription, '100%', '300', '75', '20' ) ; ?>
									</td>
								</tr>
			
							</tbody>
							</table>
						</fieldset>
					</div>
				</div>

			<input type="hidden" name="view" value="categories" />
			<input type="hidden" name="option" value="com_flic" />
			<input type="hidden" name="flic_category_id" value="<?php echo $row->flic_category_id; ?>" />
			<input type="hidden" name="task" value="" />
		<?php echo JHTML::_( 'form.token' ); ?>
		</form>
		<?php
	}

}
