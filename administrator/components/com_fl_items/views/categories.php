<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class FLItemsViewCategory
{
	function setCategoryToolbar()
	{
		JToolBarHelper::title( JText::_( 'COM_FL_ITEMS') . " - " . JText::_( 'FL_ITEMS_CATEGORIES'), 'generic.png' );
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::deleteList();
		JToolBarHelper::editList();
		JToolBarHelper::addNew('add');
		JToolBarHelper::preferences('com_fl_items', '200');
	}

	function categories( &$rows, &$pageNav, &$lists )
	{
		FLItemsViewCategory::setCategoryToolbar();
		$user =& JFactory::getUser();
		JHTML::_('behavior.tooltip');
		?>
		<form action="index.php?option=com_fl_items&view=categories" class="form-inline" method="post" name="adminForm" id="adminForm">
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

			<table class="adminlist table table-striped" id="itemList">
			<thead>
				<tr>
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHTML::_('grid.sort',   '<span class="icon-menu-2"></span>', 'p.ordering', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="20">
						<?php echo JHtml::_('grid.checkall'); ?>
					</th>
					<th nowrap="nowrap" class="title">
						<?php echo JHTML::_('grid.sort',   'Name', 'p.name', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="5%" nowrap="nowrap"> 
						<?php echo JHTML::_('grid.sort',   'Published', 'p.showItem', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="1%" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort',   'ID', 'p.item_category_id', @$lists['order_Dir'], @$lists['order'] ); ?>
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
			$saveOrder = (@$lists['order'] == 'p.ordering');
			for ($i=0, $n=count( $rows ); $i < $n; $i++) {
				$row = &$rows[$i];

				$row->id	= $row->item_category_id;
				$link		= JRoute::_( 'index.php?option=com_fl_items&view=categories&task=edit&item_category_id[]='. $row->item_category_id );

				$row->published = $row->showCategory;
				$published		= JHTML::_('grid.published', $row, $i );
				$checked		= JHTML::_('grid.id', $i, $row->id , '', 'item_category_id');
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td class="order nowrap center hidden-phone">
				        <?php
				            $iconClass = '';
				            if (!$saveOrder) {
				                $iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
				            }
				         ?>
			             <span class="sortable-handler <?php echo $iconClass ?>">
			                 <span class="icon-menu"></span>
			             </span>
			             <?php if ($saveOrder) : ?>
			             	<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="width-20 text-area-order " />
			             <?php endif; ?>
			        </td>
					<td align="center">
						<?php echo $checked; ?>
					</td>
					<td>
					<span class="editlinktip hasTip" title="Edit:: <?php echo $row->name; ?>">
						<?php
						if ( JTable::isCheckedOut($user->get ('id'), $row->checked_out ) ) {
							echo $row->name;
						} else {
							?>
							<a href="<?php echo $link; ?>">
								<?php 
								if($row->isHeader) {
									echo " - <strong>" . $row->name . "</strong> -";
								} else {
									echo $row->name;
								}
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
						<?php echo $row->item_category_id; ?>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
			</tbody>
			</table>

		<input type="hidden" name="view" value="categories" />
		<input type="hidden" name="option" value="com_fl_items" />
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

		JToolBarHelper::title( $task == 'add' ? JText::_( 'Items' ) . ': <small><small>[ '. JText::_( 'New' ) .' ]</small></small>' : JText::_( 'Items' ) . ': <small><small>[ '. JText::_( 'Edit' ) .' ]</small></small>', 'generic.png' );
		JToolBarHelper::save( 'save' );
		JToolBarHelper::apply('apply');
		JToolBarHelper::cancel( 'cancel' );
	}

	function getToggle($name, $value) {
		$field = new JFormFieldRadio();
		$field->setup(new SimpleXMLElement('<field name="'.$name.'" type="radio" size="1" default="0" class="btn-group btn-group-yesno"><option value="0">JNO</option><option value="1">JYES</option></field>'), $value);
		return $field->renderField(array('hiddenLabel'=>true));
	}

	function category( &$row, &$lists )
	{
        jimport('joomla.application.component.helper');
    	$params = JComponentHelper::getParams('com_fl_items');
		$editor=& JFactory::getEditor();
 
		FLItemsViewCategory::setOneCategoryToolbar();
		JRequest::setVar( 'hidemainmenu', 1 );
		JFormHelper::loadFieldClass('radio');
		JFormHelper::loadFieldClass('menuitem');

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

		<style type="text/css">
			.table th {
				background-color: #d9edf7 !important;
				border-color: #bce8f1 !important;
				color: #31708f !important;
				font-weight: bold;
				font-size: 1.2em;
			}
		</style>
		
		<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
			<div class="row-fluid">
				<div class="span6">
					<fieldset class="adminform">
						<legend><?php echo JText::_( 'Category' ); ?></legend>
		
						<table class="adminlist table table-striped">
							<tbody>
								<tr class="header">
									<th colspan="2">
										Details
									</th>
								</tr>
								<tr>
									<td width="30%" class="key">
										<label for="name">
											<?php echo JText::_( 'Category Name' ); ?>:
										</label>
									</td>
									<td width="70%">
										<input class="inputbox" type="text" name="name" id="name" size="50" value="<?php echo $row->name;?>" />
										<input type="hidden" name="oldname" id="oldname" value="<?php echo $row->name;?>" />
									</td>
								</tr>
								<tr>
									<td width="30%" class="key">
										<label for="isDescriptionEnabled">
											<?php echo JText::_( 'Enable Description?' ); ?>:
										</label>
									</td>
									<td width="70%">
										<?= FLItemsViewCategory::getToggle("isDescriptionEnabled", $row->isDescriptionEnabled) ?>
									</td>
								</tr>
								<tr>
									<td class="key">
										<?php echo JText::_( 'Parent Item Type' ); ?>:
									</td>
									<td>
										<?= $lists['parent_category_id']; ?>
									</td>
								</tr>
								<tr>
									<th colspan="2">
										Published
									</th>
								</tr>
								<tr>
									<td class="key">
										<?php echo JText::_( 'Published' ); ?>:
									</td>
									<td>
										<?= FLItemsViewCategory::getToggle("showCategory", $row->showCategory) ?>
									</td>
								</tr>
								<tr>
									<td class="key">
										<?php echo JText::_( 'Users can update published state?' ); ?>:
									</td>
									<td>
										<?= FLItemsViewCategory::getToggle("usersUpdatePublish", $row->usersUpdatePublish) ?>
									</td>
								</tr>
								
								
								<tr>
									<th colspan="2">
										Images
									</th>
								</tr>
								<tr>
									<td width="30%" class="key">
										<label for="hasImages">
											<?php echo JText::_( 'Allow Images?' ); ?>:
										</label>
									</td>
									<td width="70%">
										<?= FLItemsViewCategory::getToggle("hasImages", $row->hasImages) ?>
									</td>
								</tr>
								<tr>
									<td width="30%" class="key">
										<label for="isSingleImage">
											<?php echo JText::_( 'Single Image Only?' ); ?>:
										</label>
									</td>
									<td width="70%">
										<?= FLItemsViewCategory::getToggle("isSingleImage", $row->isSingleImage) ?>
									</td>
								</tr>
								<tr>
									<td width="30%" class="key">
										<label for="hasImages">
											<?php echo JText::_( 'Enable Image Captions?' ); ?>:
										</label>
									</td>
									<td width="70%">
										<?= FLItemsViewCategory::getToggle("hasImageCaptions", $row->hasImageCaptions) ?>
									</td>
								</tr>
								<tr>
									<td width="30%" class="key">
										<label for="description" title="">
											<?php echo JText::_( 'Max Image Dimensions (Width x Height)' ); ?>:
										</label>
									</td>
									<td width="70%">
										<input class="inputbox" type="text" name="imageWidth" id="imageWidth" size="25" style="width: 50px;" value="<?php echo $row->imageWidth;?>" /> x 
										<input class="inputbox" type="text" name="imageHeight" id="imageHeight" size="25" style="width: 50px;" value="<?php echo $row->imageHeight;?>" /> px
									</td>
								</tr>
								<tr>
									<td width="30%" class="key">
										<label for="isSingleImage">
											<?php echo JText::_( 'Force Crop to Exact Image Size?' ); ?>:
										</label>
									</td>
									<td width="70%">
										<?= FLItemsViewCategory::getToggle("forceExactImageSize", $row->forceExactImageSize) ?>
									</td>
								</tr>
								<tr>
									<td width="30%" class="key">
										<label for="isSingleImage">
											<?php echo JText::_( '"no-image" Image' ); ?>:
										</label>
									</td>
									<td width="70%">
										<?php 
										if($row->noimage && $row->item_category_id) {
											echo "<img src='/images/fl_items/".$row->noimage."?v=". rand(1,999999) ."' style='max-width: 75px; max-height: 75px;'>";
										}
										?>
										<input class="inputbox" type="file" name="newnoimage" id="newnoimage">
									</td>
								</tr>
                                <tr>
                                    <td width="30%" class="key">
                                        <label for="addWatermark" title="Add watermark to uploaded images?">
                                            <?php echo JText::_( 'Add Watermark?' ); ?>:
                                        </label>
                                    </td>
                                    <td width="70%">
                                        <?= FLItemsViewCategory::getToggle("addWatermark", $row->addWatermark) ?>
                                    </td>
                                </tr>
								<tr>
									<td width="30%" class="key">
										<label for="watermarkImage">
											<?php echo JText::_( 'Watermark Image' ); ?>:
										</label>
									</td>
									<td width="70%">
										<?php 
										if($row->watermarkImage && $row->item_category_id) {
											echo "<img src='/images/fl_items/".$row->watermarkImage."?v=". rand(1,999999) ."' style='max-width: 75px; max-height: 75px;'>";
										}
										?>
										<input class="inputbox" type="file" name="newWatermarkImage" id="newWwatermarkImage">
									</td>
								</tr>
                                <tr>
                                    <td width="30%" class="key">
                                        <label for="watermarkPosition" title="Where watermark should be positioned on your image?">
                                            <?php echo JText::_( 'Watermark Position?' ); ?>:
                                        </label>
                                    </td>
                                    <td width="70%">
                                    	<?php
                                    	$watermarkPositions = array(
                                    		array("c" => 0, "name" => "Center"),
                                    		array("c" => 1, "name" => "Top Left"),
                                    		array("c" => 2, "name" => "Top Right"),
                                    		array("c" => 3, "name" => "Bottom Left"),
                                    		array("c" => 4, "name" => "Bottom Right")
										);
                                    	echo JHTML::_('select.genericlist', $watermarkPositions, 'watermarkPosition', 'class="inputbox" size="1"','c', 'name', $row->watermarkPosition );
                                    	?>
                                    </td>
                                </tr>
								
								<tr>
									<th colspan="2">
										Other Options
									</th>
								</tr>
								<tr>
									<td width="30%" class="key">
										<label for="description" title="If enabled, all links to this menu type will be directed to selected Menu Item. Must be FL Item type.">
											<?php echo JText::_( 'Force Links to Menu Item?' ); ?>:
										</label>
									</td>
									<td width="70%">
										<?= FLItemsViewCategory::getToggle("isForceMenuItem", $row->isForceMenuItem) ?>
										<?php
										$field = new JFormFieldMenuitem();
										$field->setup(new SimpleXMLElement('<field name="menuId" type="menuitem" default="0" label="Select a menu item" description="Select a menu item" />'), $row->menuId);
										echo $field->renderField(array('hiddenLabel'=>true));
										?>
										<script>
											jQuery('input[name=isForceMenuItem]').change(function(){
												var value = jQuery( 'input[name=isForceMenuItem]:checked' ).val();
												if(value == 1) {
													jQuery("#menuId").prop('disabled', false);
												} else {
													jQuery("#menuId").prop('disabled', true);
												}
											});
											<?php if(!$row->isForceMenuItem) {
												echo 'jQuery("#menuId").prop("disabled", true);';
											} ?>
										</script>
									</td>
								</tr>
                                <tr>
                                    <td width="30%" class="key">
                                        <label for="isNewFirst">
                                            <?php echo JText::_( 'Put new items first?' ); ?>:
                                        </label>
                                    </td>
                                    <td width="70%">
                                    	<?= FLItemsViewCategory::getToggle("isNewFirst", $row->isNewFirst) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%" class="key">
                                        <label for="isFeaturedEnabled" title="">
                                            <?php echo JText::_( 'Enable "Featured" option?' ); ?>:
                                        </label>
                                    </td>
                                    <td width="70%">
                                    	<?= FLItemsViewCategory::getToggle("isFeaturedEnabled", $row->isFeaturedEnabled) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%" class="key">
                                        <label for="usersEditOnly" title="If enabled, users can not create/delete/publish/unpublish.">
                                            <?php echo JText::_( 'Users can ONLY EDIT items?' ); ?>:
                                        </label>
                                    </td>
                                    <td width="70%">
                                    	<?= FLItemsViewCategory::getToggle("usersEditOnly", $row->usersEditOnly) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%" class="key">
                                        <label for="isLinkToUser" title="This allows a front-end users to log in and make changes.">
                                            <?php echo JText::_( 'Link to front-end user?' ); ?>:
                                        </label>
                                    </td>
                                    <td width="70%">
                                        <?= FLItemsViewCategory::getToggle("isLinkToUser", $row->isLinkToUser) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%" class="key">
                                        <label for="isLinkToUser" title="This hides Type from left-side menu and is on accessable from the Child category.">
                                            <?php echo JText::_( 'Is Hidden Parent' ); ?>:
                                        </label>
                                    </td>
                                    <td width="70%">
                                        <?= FLItemsViewCategory::getToggle("isHiddenParent", $row->isHiddenParent) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%" class="key">
                                        <label for="isLinkToUser" title="Heading for left menu.">
                                            <?php echo JText::_( 'Is Category Header?' ); ?>:
                                        </label>
                                    </td>
                                    <td width="70%">
                                        <?= FLItemsViewCategory::getToggle("isHeader", $row->isHeader) ?>
                                    </td>
                                </tr>
							</tbody>
						</table>
					</fieldset>
				</div>
			</div>

			<input type="hidden" name="ordering" value="<?php echo $row->ordering; ?>" />
			<input type="hidden" name="view" value="categories" />
			<input type="hidden" name="option" value="com_fl_items" />
			<input type="hidden" name="item_category_id" value="<?php echo $row->item_category_id; ?>" />
			<input type="hidden" name="task" value="" />
		<?php echo JHTML::_( 'form.token' ); ?>
		</form>
		<?php
	}

}
