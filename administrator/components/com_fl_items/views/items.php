<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class FLItemsViewItem
{
	function setItemsToolbar($categoryId, $canOnlyEdit = false)
	{
		JToolBarHelper::title( JText::_( 'COM_FL_ITEMS') . " - Items", 'generic.png' );
		if($categoryId) {
			JToolBarHelper::publishList();
			JToolBarHelper::unpublishList();
			if(!$canOnlyEdit) {
				JToolBarHelper::deleteList();
			}
			JToolBarHelper::editList();
			if(!$canOnlyEdit) {
				JToolBarHelper::addNew('add');
				JToolBarHelper::custom( 'batchadd', 'generic.png', 'generic.png', 'Batch Add', false, false );
				JToolBarHelper::custom( 'uploadcsv', 'generic.png', 'generic.png', 'Import CSV', false, false );
				JToolBarHelper::custom( 'exportcsv', 'generic.png', 'generic.png', 'Export CSV', false, false );
			}
		}
		// JToolBarHelper::preferences('com_fl_items', '200');
	}
	
	function welcome() {
		JToolBarHelper::title( JText::_( 'COM_FL_ITEMS') . " - Welcome", 'generic.png' );
		?>
		<h3>No item types have been created.</h3>
		<?php
	}
	
	function selectInstructions() {
		JToolBarHelper::title( JText::_( 'COM_FL_ITEMS') . " - Select", 'generic.png' );
		?>
		<h3>← Which area of the site would you like to manage? Select one.</h3>
		<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><span style="color: white; font-size: 4px;" class='pull-right'>Hi</span>
		<?php
	}

	function items( &$rows, &$pageNav, &$lists )
	{
		$user =& JFactory::getUser();
		$isroot = $user->authorise('core.admin');
		FLItemsViewItem::setItemsToolbar($lists['item_category_id'], $lists['usersEditOnly'] && !$isroot);

		$editor=& JFactory::getEditor();
		JHTML::_('behavior.tooltip');
		
		?>

		<?php if($lists['isDescriptionEnabled']) { ?>
			<h4 style="background: #f9f9f9; border: 1px solid #ddd; padding: 8px; cursor: pointer;" class='toggle-description'>
				<a class='toggle-description' href="#"><strong>Edit Category Description</strong></a><small><em> : (<?php echo strip_tags(substr($lists['categoryDescription'], 0, 150));?>...)</em></small>
			</h4>
			<div class="category-lightbox" style="display:none; position: fixed; width: 100%; height: 100%; left: 0; top: 0; background: rgba(0,0,0,0.7); z-index: 9999; overflow-y: auto;">
				<div class="close-lb" style="width: 100%; height: 100%; position: absolute; top: 0; left: 0; cursor: pointer;"></div>
				<div class="category-description" style="max-width: 90vw; width: 1250px; margin: 0 auto; margin-top: 10%; background: #fff; padding: 15px; position: relative;">
					<form action="index.php?option=com_fl_items&item_category_id=<?php echo $lists['item_category_id']; ?>" class="form-inline" method="post">
						<h3>Edit: <?php echo $lists['categoryName']; ?> Description</h3>
						<? echo $editor->display( 'description',  $lists['categoryDescription'], '100%', '300', '75', '20' ) ; ?>
						<div class="save-buttons text-right">
							<button href="#" class='btn btn-success' style="margin-top: 9px;">Save</button>
							<a class='btn btn-danger close-lb' style="margin-top: 9px;">Cancel</a>
						</div>
						<input type="hidden" name="item_category_id" value="<?php echo $lists['item_category_id']; ?>" />
						<input type="hidden" name="c" value="item" />
						<input type="hidden" name="option" value="com_fl_items" />
						<input type="hidden" name="task" value="updateDescription" />
					</form>
				</div>
			</div>
			<script>
				jQuery(".toggle-description").click(function() {
					jQuery(".category-lightbox").show();
				});
				jQuery(".close-lb").click(function() {
					jQuery(".category-lightbox").hide();
				});
			</script>
		<?php } ?>
		<form action="index.php?option=com_fl_items" class="form-inline" method="post" name="adminForm" id="adminForm">
			<?php if(isset($_REQUEST['item_category_id'])) { ?>
				<table>
					<tr>
						<td align="left" width="100%">
							<input type="text" class="form-control" name="search" id="search" placeholder="Search..." value="<?php echo $lists['search'];?>">
							<button class="btn btn-primary">Search</button>
							<button class="btn btn-default" id="clear-btn">Clear</button>
							<script>
								jQuery("#clear-btn").click(function() {
									jQuery("#search").val("");
								});
							</script>
						</td>
						<?php if($lists['hasParent']) { ?>
							<td nowrap="nowrap" style="padding-right: 5px;">
								<a class="btn btn-primary" href="<?= JRoute::_( 'index.php?option=com_fl_items&item_category_id='. $lists['parentCategoryId'] ) ?>">
									<strong>Edit Parents</strong> (<?php echo $lists['parentCategoryName'];?>)
								</a>
							</td>
						<?php } ?>
						<? if($isroot) { ?>
							<td nowrap="nowrap" style="padding-right: 5px;">
								<a class="btn btn-info" href="<?= JRoute::_( 'index.php?option=com_fl_items&view=categories&task=edit&item_category_id[]='. $_REQUEST['item_category_id'] ) ?>">Edit <strong>Item Type</strong></a>
							</td>
							<td nowrap="nowrap" style="padding-right: 25px;">
								<a class="btn btn-info" href="<?= JRoute::_( 'index.php?option=com_fl_items&view=properties&categoryId='. $_REQUEST['item_category_id'] ) ?>">Edit <strong>Properties</strong></a>
							</td>
						<? } ?>
						<td nowrap="nowrap">
							<?php
							echo $lists['state'];
							?>
						</td>
						<td nowrap="nowrap">
							<?php
							echo $lists['limit'];
							?>
						</td>
					</tr>
				</table>
			<?php } ?>

			<table class="adminlist table table-striped" id="itemList">
			<thead>
				<tr>
					<th width="1%" class="nowrap center hidden-phone">
                        <?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'i.ordering', $lists['order_Dir'], $lists['order'], null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
                    </th>
					<th width="20">
						<?php echo JHtml::_('grid.checkall'); ?>
					</th>
					<th nowrap="nowrap" class="title">
						<?php echo JHTML::_('grid.sort', 'Name', 'i.name', @$lists['order_Dir'], @$lists['order']); ?>
					</th>
					<?php if($lists['hasParent']) { ?>
						<th nowrap="nowrap">
							<?php echo JHTML::_('grid.sort', 'Parent', 'parentName', @$lists['order_Dir'], @$lists['order']); ?>
						</th>
					<?php }
					foreach($lists['props'] as $p) {
						print "<th>";
                            echo JHTML::_('grid.sort', $p['caption'], 'prop.'.$p['name'], @$lists['order_Dir'], @$lists['order']);
						print "</th>";
					}
					?>
					<?php if ($lists['isFeaturedEnabled']) { ?>
						<th width="5%" nowrap="nowrap">
							<?php echo JHTML::_('grid.sort', 'Featured', 'i.isFeatured', @$lists['order_Dir'], @$lists['order']); ?>
						</th>
					<?php } ?>
					<th width="5%" nowrap="nowrap"> 
						<?php echo JHTML::_('grid.sort', 'Published', 'i.showItem', @$lists['order_Dir'], @$lists['order']); ?>
					</th>
					<th width="1%" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort', 'ID', 'i.item_id', @$lists['order_Dir'], @$lists['order']); ?>
					</th>
				</tr>
			</thead> 
			<tfoot>
				<tr>
					<td colspan="13">
						<?php echo $pageNav -> getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php
			$k = 0;
			$ordering   = (@$lists['order'] == 'i.ordering');
			$saveOrder = (@$lists['order'] == 'i.ordering');
			for ($i=0, $n=count( $rows ); $i < $n; $i++) {
				$row = &$rows[$i];

				$row->id	= $row->item_id;
				$link		= JRoute::_( 'index.php?option=com_fl_items&c=item&task=edit&item_category_id='.$row->item_category_id.'&item_id[]='. $row->id );

				$row->published = $row->showItem;
				$published		= JHTML::_('grid.published', $row, $i );
				$checked		= JHTML::_('grid.id', $i, $row->id , '', 'item_id');
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
					<span class="editlinktip hasTip" title="<?php echo $row->name ?>">
						<?php
						if ( JTable::isCheckedOut($user->get ('id'), $row->checked_out ) ) {
							echo $row->name;
						} else {
							?>
							<a href="<?php echo $link; ?>">
								<?php
								echo $row->name;
								?>
							</a>
						<?php
						}
						?>
						</span>
					</td>
					<?php 
					if($lists['hasParent']) { 
						echo '<td align="center">';
							echo $row->parentName;
						echo '</td>';
					}	
					?>
					<?php
					foreach($lists['props'] as $p) {
						print "<td>";
							$thisProp = $lists['propdata'][$row->item_id][$p['caption']];
							if($thisProp->type == "select") {
								print $thisProp->selectValue;
							} else if(strpos($thisProp->type, "link-") === 0) {
								print $thisProp->selectItemValue;
							} else {
								print $thisProp->value;
							}
						print "</td>";
					}?>

					<?php if ($lists['isFeaturedEnabled']) { ?>
						<td>
							<?php
							if ($row -> isFeatured == 1) {
								echo 'Yes';
							} else {
								echo 'No';
							}
							?>
						</td>
					<?php } ?>

					<td align="center">
						<?php echo $published; ?>
					</td>
					<td align="center">
						<?php echo $row->id; ?>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
				}
			?>
			</tbody>
			</table>

		<input type="hidden" name="item_category_id" value="<?php echo $lists['item_category_id']; ?>" />
		<input type="hidden" name="c" value="item" />
		<input type="hidden" name="option" value="com_fl_items" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir']; ?>" />
		<?php echo JHTML::_('form.token'); ?>
		</form>
		
		<script>
			jQuery("#limit").change(function() {
				jQuery("#adminForm").submit();
			})
		</script>
		<?php
		}

		function setItemToolbar()
		{
			$task = JRequest::getVar( 'task', '', 'method', 'string');

			JToolBarHelper::title( $task == 'add' ? JText::_( 'Items' ) . ': <small><small>[ '. JText::_( 'New' ) .' ]</small></small>' : JText::_( 'Items' ) . ': <small><small>[ '. JText::_( 'Edit' ) .' ]</small></small>', 'generic.png' );
			JToolBarHelper::save( 'save' );
			JToolBarHelper::apply('apply');
			JToolBarHelper::cancel( 'cancel' );
		}

		function item( &$row, &$lists )
		{
			jimport('joomla.application.component.helper');
			$params = JComponentHelper::getParams('com_fl_items');
			$editor=& JFactory::getEditor();
	
			FLItemsViewItem::setItemToolbar();
			JRequest::setVar( 'hidemainmenu', 1 );
			JFormHelper::loadFieldClass('radio');
			JFormHelper::loadFieldClass('user');
			
			?>
			<script>
				var jsItemId = 0;
				<?php if($row->item_id) { ?>
					jsItemId = <?php echo $row->item_id;?>;
				<?php } ?>
				var jsIsSingleImage = false;
				<?php if($lists['categoryData']->isSingleImage) { ?>
					jsIsSingleImage = true;
				<?php } ?>
			</script>
			
			<?php
			$doc = JFactory::getDocument();
			JHtml::_('jquery.framework');
			$doc->addScript('//code.jquery.com/ui/1.12.1/jquery-ui.js');
			$doc->addStyleSheet('//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
			$doc->addStyleSheet('/administrator/components/com_fl_items/assets/css/glyphicons.css');
			$doc->addScript('/administrator/components/com_fl_items/assets/cropper.js');
			$doc->addScript('/administrator/components/com_fl_items/assets/jquery-cropper.js');
			$doc->addScript('/administrator/components/com_fl_items/assets/fl-item-crop.js');
			$doc->addStyleSheet('/administrator/components/com_fl_items/assets/cropper.min.css');
			
			// Create and fill Item Object
			$thisItemForm = new FliItem(array(), null);
			foreach($lists['properties'] as $property) {
				$property->item_id = $row->item_id;
				$property->value = $lists['propertyValues'][$property->item_property_id];
				$property->options = $lists['options'][$property->item_property_id];
				$thisItemForm->addProperty($property->type, $property);
			}
			
			?>
			<script language="javascript" type="text/javascript">
				function submitbutton(pressbutton) {
					var form = document.adminForm;
					if (pressbutton == 'cancel') {
						submitform( pressbutton );
						return;
					}
					// do field validation
					if (form.name.value == "") {
						alert( "<?php echo JText::_('You must provide a name.', true); ?>");
					} else {
						submitform( pressbutton );
					}
				}
			</script>

			<div class="crop-wrapper" style="display: none;">
				<div class="crop-window">
					<h4 style="margin: 0 0 5px;">Crop Your Image</h4>
					<div class='crop-border'>
						<div class='crop-container'>
						  	<img src='/administrator/components/com_fl_items/assets/icon-publish.png' id='crop-canvas' width='400' height='300'></img>
						</div>
					</div>
					<div class='crop-buttons'>
						<a id='crop-cancel' href="javascript:;" class="btn btn-primary btn-danger">Cancel</a>
						<a id='crop-save' href="javascript:;" class="btn btn-primary btn-success">Crop & Save</a>
					</div>
				</div>
			</div>
			
			<div class="image-progress" style="display: none;">
				<div class="crop-window progress-window">
					<h4 style="margin: 0 5px 5px;">Processing</h4>
					<div class="loader-bar-wrapper">
						<div class="line stripesLoader" style="background-position:100%; background-color:green"></div>
					</div>
				</div>
			</div>
			
			<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
				<div class="row-fluid">
    				<div class="span7">
    					<fieldset class="adminform">
    						
    						<legend><?php echo JText::_('Item Info'); ?></legend>
    						<table class="adminlist table table-striped">
    							<tbody>
	                                <tr>
	                                    <td width="20%" class="key">
	                                        <label for="name">
	                                            <?php echo JText::_('FL_ITEMS_TYPE') . " Name"; ?>:
	                                        </label>
	                                    </td>
	                                    <td width="80%">
	                                        <input class="inputbox" type="text" name="name" id="name" size="50" value="<?php echo htmlspecialchars($row -> name); ?>" />
	                                    </td>
                                	</tr>
                                    <?php if ($lists['parentSelect']) { ?>
                                    	<tr>
		                                    <td width="20%" class="key">
		                                        <label for="name">
		                                            <?php echo JText::_('Parent'); ?>:
		                                        </label>
		                                    </td>
		                                    <td width="80%">
		                                        <?= $lists['parentSelect']; ?>
		                                    </td>
	                                	</tr>
                                    <?php } ?>
    								<?php
									$thisItemForm->outputAdminForm();
    								?>
    							</tbody>
    						</table>
    					</fieldset>
    				</div>
    				<div class="span5">
                        <fieldset class="adminform">
                            <legend><?php echo JText::_('Item Details'); ?></legend>
                            <table class="adminlist table table-striped">
	                            <tbody>
	                                <tr>
	                                    <td class="key">
	                                        <?php echo JText::_('Published'); ?>:
	                                    </td>
	                                    <td>
	                                        <?php
											$field = new JFormFieldRadio();
											$field->setup(new SimpleXMLElement('<field name="showItem" type="radio" size="1" default="0" class="btn-group btn-group-yesno"><option value="0">JNO</option><option value="1">JYES</option></field>'), $row->showItem);
											echo $field->renderField(array('hiddenLabel'=>true));
											?>
	                               			<input type="hidden" name="ordering" value="<?php echo $row->ordering; ?>" />
	                                    </td>
	                                </tr>
	                                <?php if ($lists['categoryData']->isFeaturedEnabled) { ?>
		                                <tr>
		                                    <td class="key">
		                                        <?php echo JText::_('Featured'); ?>:
		                                    </td>
		                                    <td>
		                                        <?php
												$field = new JFormFieldRadio();
												$field->setup(new SimpleXMLElement('<field name="isFeatured" type="radio" size="1" default="0" class="btn-group btn-group-yesno"><option value="0">JNO</option><option value="1">JYES</option></field>'), $row->isFeatured);
												echo $field->renderField(array('hiddenLabel'=>true));
												?>
		                                    </td>
		                                </tr>
	                                <?php } ?>
	                                <tr>
	                                    <td width="20%" class="key">
	                                        <label for="name">
	                                            Alias:
	                                        </label>
	                                    </td>
	                                    <td width="80%">
	                                        <input class="inputbox" type="text" name="alias" id="alias" size="50" value="<?php echo htmlspecialchars($row -> alias); ?>" placeholder="Auto-generate from title" />
	                                    </td>
	                            	</tr>
	                                <?php if ($lists['categoryData']->isLinkToUser) { ?>
		                                <tr>
		                                    <td class="key">
		                                        <?php echo JText::_('User Account'); ?>:
		                                    </td>
		                                    <td>
		                                        <?php
												$field = new JFormFieldUser();
												$field->setup(new SimpleXMLElement('<field name="linked_user_id" 
													type="user"
													label="User Account" />'), $row->linked_user_id);
												echo $field->renderField(array('hiddenLabel'=>true));
												?>
		                                    </td>
		                                </tr>
	                                <?php } ?>
	                                <tr>
	                                    <td width="20%" class="key">
	                                        <label for="item_category_id">
	                                            <?php echo JText::_('FL_ITEMS_CATEGORY'); ?>:
	                                        </label>
	                                    </td>
	                                    <td width="80%">
	                                        <strong><? echo $lists['categoryData']->name; ?></strong>
	                                		<input type="hidden" name="item_category_id" value="<?php echo $row->item_category_id; ?>" />
	                                    </td>
	                                </tr>
	                            </tbody>
                            </table>
                        </fieldset>
                    </div>
                </div>
			<?php if($lists['categoryData']->hasImages == 1) { ?>
				<div class="row-fluid">
					<div class="span7">
						<fieldset class="adminform">
							<legend>
								<?php echo JText::_('Images'); ?>
								<?php if($lists['categoryData']->imageWidth && $lists['categoryData']->imageHeight) 
									{ ?>
										<small>
											Recommended Image Size (W x H): <?php echo $lists['categoryData']->imageWidth;?> x <?php echo $lists['categoryData']->imageHeight;?> px
										</small>
									<?php } ?>
							</legend>
							
						  	<div class="reorder-pane">
								<?php if($lists['categoryData']->isSingleImage == 0) { ?>
									<p>
										Click and drag images to change ordering. Click "Save" to confirm.
									</p>
								<?php } ?>
								<ul id="sortable" class="reorder-photos-list">
									<?php
									foreach ($lists['itemImage'] AS $galleryImageRow) {
										$id = $galleryImageRow->item_image_id;
										$isActive = $galleryImageRow->showImage;
										echo "<li id='image_li_$id' data-imgid='$id' class='ui-state-default " . ($isActive ? "" : "closed") . "'>";
											echo '<img src="/images/fl_items/' . $galleryImageRow->item_id . '/original/' . $galleryImageRow->filename . '" style="width: 100%; height: 100%; object-fit: contain;" />';
											echo '<div class="img-del"><img title="Click to Delete" src="/administrator/components/com_fl_items/assets/icon-delete.png"></div>';
											if($lists['categoryData']->hasImageCaptions) {
												if(strlen($galleryImageRow->caption)) {
													echo '<div class="img-cap"><img title="Click to Edit Caption" src="/administrator/components/com_fl_items/assets/icon-caption-filled.png"></div>';
												} else {
													echo '<div class="img-cap"><img title="Click to Edit Caption" src="/administrator/components/com_fl_items/assets/icon-caption.png"></div>';
												}
												echo '<div class="edit-caption" style="display: none;"><textarea class="caption-area" name="caption_'.$id.'" placeholder="Image Caption..."  name="">'.$galleryImageRow->caption.'</textarea></div>';
											}
											if($isActive) {
												echo '<div class="img-pub"><img title="Click to Unpublish" src="/administrator/components/com_fl_items/assets/icon-publish.png"></div>';
											} else {
												echo '<div class="img-pub"><img title="Click to Publish" src="/administrator/components/com_fl_items/assets/icon-publish-toggle.png"></div>';
											}
											echo '<input name="delete_gallery_image_'.$id.'" id="delete_gallery_image_'.$id.'" type="hidden" value="0">';
											echo '<input name="showImage_'.$id.'" id="showImage_'.$id.'" type="hidden" value="'.$isActive.'">';
										echo '</li>';
									}
									?>
								</ul>
								<input type="hidden" id="img-ordering" name="img-ordering" value="">
								<script>
									jQuery(document).ready(function() {
										jQuery('ul.reorder-photos-list').sortable();
										jQuery('ul.reorder-photos-list').disableSelection();
										
										jQuery("#sortable").on("click", ".img-pub", function() {
											var imgId = jQuery(this).parent().attr("data-imgid");
											if(jQuery(this).parent().is(".closed")) {
												jQuery(this).parent().removeClass("closed");
												jQuery(this).find("img").attr("src", "/administrator/components/com_fl_items/assets/icon-publish.png");
												jQuery(this).find("img").attr("title", "Click to Unpublish");
												jQuery("#showImage_"+imgId).val(1);
											} else {
												jQuery(this).parent().addClass("closed");
												jQuery(this).find("img").attr("src", "/administrator/components/com_fl_items/assets/icon-publish-toggle.png");
												jQuery(this).find("img").attr("title", "Click to Publish");
												jQuery("#showImage_"+imgId).val(0);
											}
										});
										jQuery("#sortable").on("click", ".img-cap", function() {
											jQuery(this).toggleClass("active");
											if(jQuery(this).is(".active")) {
												jQuery(this).next().show();
												jQuery(this).find("img").attr("src", "/administrator/components/com_fl_items/assets/icon-caption-toggle.png");
											} else {
												jQuery(this).next().hide();
												var val = jQuery(this).next().find(".caption-area").val();
												if(val.length) {
													jQuery(this).find("img").attr("src", "/administrator/components/com_fl_items/assets/icon-caption-filled.png");
												} else {
													jQuery(this).find("img").attr("src", "/administrator/components/com_fl_items/assets/icon-caption.png");
												}
											}
										});
										jQuery("#sortable").on("click", ".img-del", function() {
											var imgId = jQuery(this).parent().attr("data-imgid");
											if(jQuery(this).parent().is(".delete")) {
												jQuery(this).parent().removeClass("delete");
												jQuery(this).find("img").attr("src", "/administrator/components/com_fl_items/assets/icon-delete.png");
												jQuery(this).find("img").attr("title", "Click to Delete");
												jQuery("#delete_gallery_image_"+imgId).val(0);
											} else {
												jQuery(this).parent().addClass("delete");
												jQuery(this).find("img").attr("src", "/administrator/components/com_fl_items/assets/icon-delete-toggle.png");
												jQuery(this).find("img").attr("title", "Click to Cancel Delete");
												jQuery("#delete_gallery_image_"+imgId).val(1);
											}
										});
		
										jQuery(".btn-img-properties").click(function() {
											jQuery(".edit-pane").show();
											jQuery(".reorder-pane").hide();
											jQuery(".btn-img-ordering").removeClass("active");
											jQuery(".btn-img-properties").addClass("active");
										});
										jQuery(".btn-img-ordering").click(function() {
											jQuery(".edit-pane").hide();
											jQuery(".reorder-pane").show();
											jQuery(".btn-img-properties").removeClass("active");
											jQuery(".btn-img-ordering").addClass("active");
										});
										jQuery("#sortable").on("keypress", ".caption-area", function(e) {
									        var code = (e.keyCode ? e.keyCode : e.which);
									        if (code == 13) {
									            jQuery(this).parent().prev().click();
									            return false;
									        }
									    });
		
										jQuery('#adminForm').submit(function() {
											var imgOrdering = [];
											jQuery('ul.reorder-photos-list li').each(function() {
												imgOrdering.push(jQuery(this).attr('data-imgid'));
											});
											jQuery('#img-ordering').val(''+imgOrdering);
										});
									});
		
								</script>
							</div>
						</fieldset>		
					</div>
					<div class="span5 upload-images" <?php if($lists['categoryData']->isSingleImage && count($lists['itemImage'])) { echo 'style="display: none;"';} ?>>
						<fieldset class="table table-striped">
							<?php if(!($lists['categoryData']->isSingleImage && count($lists['itemImage']))) { ?>
								<legend><?php echo JText::_('Upload Images'); ?> 
									<?php if($lists['categoryData']->imageWidth && $lists['categoryData']->imageHeight) 
									{ ?>
										<small>
											Recommended Image Size (W x H): <?php echo $lists['categoryData']->imageWidth;?> x <?php echo $lists['categoryData']->imageHeight;?> px
										</small>
									<?php } ?>
								</legend>
							<?php } ?>
			
							<table class="admintable" style="width: 100%;">
								<tbody>
									<tr>
										<td class="key">
											<label for="uploadNewImage">
												<?php echo JText::_('New Image'); ?>:
											</label>
										</td>
										<td width="70%">
											<?php if($row->item_id) { ?>
												<div id="gallery-alert"></div>
												<label>
													<input class="inputbox hidden" type="file" name="new_filename_1" id="new_filename_1" />
													<div id="imgdrop-gallery" class="img-upload-wrap img-drop-gallery"></div><br>
													<div class="btn btn-primary btn-success">Browse New Image</div>
												</label>
												<script>
													jQuery('#new_filename_1').on('change', function () {
														<?php
														if($lists['categoryData']->forceExactImageSize == 1) { ?>
															setDimension(<?php echo $lists['categoryData']->imageWidth;?>, <?php echo $lists['categoryData']->imageHeight;?>);
														<?php } else { ?>
															setDimension(0,0,<?php echo $lists['categoryData']->imageWidth;?>, <?php echo $lists['categoryData']->imageHeight;?>);
														<?php } ?>
														readFile(this); 
													});
													jQuery('#imgdrop-gallery').on(
													    'drop',
													    function(e){
													        if(e.originalEvent.dataTransfer && e.originalEvent.dataTransfer.files.length) {
													        	jQuery('.img-upload-wrap').removeClass('hover').removeClass('on');
													            e.preventDefault();
													            e.stopPropagation();
																var thisInput = document.getElementById('new_filename_1');
																thisInput.files = e.originalEvent.dataTransfer.files;
																<?php
																if($lists['categoryData']->forceExactImageSize == 1) { ?>
																	setDimension(<?php echo $lists['categoryData']->imageWidth;?>, <?php echo $lists['categoryData']->imageHeight;?>);
																<?php } else { ?>
																	setDimension(0,0,<?php echo $lists['categoryData']->imageWidth;?>, <?php echo $lists['categoryData']->imageHeight;?>);
																<?php } ?>
																
													            readFile(thisInput);
													        }
													    }
													);
												</script>
											<?php } else if($lists['categoryData']->addWatermark && $lists['categoryData']->watermarkImage ) { ?>
												<strong>You must create the item before using the crop tool.<br>Click the "Save" button to continue.</strong>
											<?php } else { ?>
												<?php if($lists['categoryData']->forceExactImageSize == 0) { ?>
													<input class="inputbox" type="file" name="new_filename_1" id="new_filename_1" />
													<div><strong><small><em>To use the crop tool, click the "Save" button.</em></small></strong></div>
												<?php } else { ?>
													<strong>You must create the item before using the crop tool.<br>Click the "Save" button to continue.</strong>
												<?php } ?>
											<?php } ?>
										</td>
									</tr>
									<?php if( $lists['categoryData']->isSingleImage == 0 && !($row->item_id == 0 && $lists['categoryData']->addWatermark && $lists['categoryData']->watermarkImage) ) { ?>
									<tr>
										<td class="key">
											<label for="uploadZipFile">
												Mass Upload <small>(.zip)</small>:
											</label>
										</td>
										<td width="70%">
											<input class="inputbox" type="file" name="uploadZipFile" id="uploadZipFile" />
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</fieldset>
					</div>
				</div>
				<? } ?>
	
				<input type="hidden" name="c" value="item" />
				<input type="hidden" name="option" value="com_fl_items" />
				<input type="hidden" name="item_id" value="<?php echo $row->item_id; ?>" />
				<input type="hidden" name="task" value="" />
			<?php echo JHTML::_('form.token'); ?>
			</form>
			<script>
				jQuery(".delete-file-checkbox").change(function() {
					jQuery(this).parent().next().prop('disabled', function(i, v) { return !v; });
				});
				jQuery("#adminForm").submit(function() {
					var task = jQuery("input[name=task]").val();
					if(task === "apply" || task === "save") {
						if(jQuery("#name").val().length === 0) {
							alert("Item Name is required!");
							return false;
						}
					}
					return true;
				});
			</script>
		<?php
	}	
	function setBatchToolbar()
	{
		$task = JRequest::getVar( 'task', '', 'method', 'string');

		JToolBarHelper::title( 'Batch Add', 'generic.png' );
		JToolBarHelper::apply('batchSave');
		JToolBarHelper::cancel( 'cancel' );
	}

	function batchAdd( &$row, &$lists )
	{
		jimport('joomla.application.component.helper');
		$params = JComponentHelper::getParams('com_fl_items');
		$editor=& JFactory::getEditor();

		FLItemsViewItem::setBatchToolbar();
		JRequest::setVar( 'hidemainmenu', 1 );
		
		?>
		
		<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
			<?php for($i = 0; $i < 20; $i++) { ?>
				<?php
				$thisItemForm = new FliItem(array(), null);
				foreach($lists['properties'] as $property) {
					$property->item_id = $i;
					$property->value = $lists['propertyValues'][$property->item_property_id];
					$property->options = $lists['options'][$property->item_property_id];
					$thisItemForm->addProperty($property->type, $property);
				}
				?>
				<div class="row-fluid">
    				<div class="span8">
    					<fieldset class="adminform">
    						
    						<legend><?php echo JText::_('Item #' . ($i+1) . ' Properties'); ?></legend>
    						<table class="adminlist table table-striped">
    							<tbody>
	                                <tr>
	                                    <td width="20%" class="key">
	                                        <label for="name">
	                                            <?php echo JText::_('FL_ITEMS_TYPE') . " Name"; ?>:
	                                        </label>
	                                    </td>
	                                    <td width="80%">
	                                        <input class="inputbox" type="text" name="name-<?php echo $i;?>" id="name" size="50" value="<?php echo htmlspecialchars($row -> name); ?>" />
	                                    </td>
                                	</tr>
    								<?php
    								$thisItemForm->outputBatchAdminForm($i);
    								?>
    							</tbody>
    						</table>
    					</fieldset>
    				</div>
                </div>
            <?php }  ?>
			<style type="text/css">
				.edit-fl_gallery table.fl_gallery-images td {
					vertical-align: top;
					text-align: left !important;
					padding: 5px 3px;
					border-top: 1px solid #eee;
				}
			</style>

			<input type="hidden" name="c" value="item" />
			<input type="hidden" name="batch" value="1" />
			<input type="hidden" name="option" value="com_fl_items" />
			<input type="hidden" name="item_category_id" value="<?php echo $_POST['item_category_id']; ?>" />
			<input type="hidden" name="task" value="" />
		<?php echo JHTML::_('form.token'); ?>
		</form>
		<script>
			jQuery(".delete-file-checkbox").change(function() {
				jQuery(this).parent().next().prop('disabled', function(i, v) { return !v; });
			});
		</script>
		<?php
	}	

	function uploadSelect($categoryId) { ?>

		<h1>Select CSV file to import</h1>
		<p>Include titles in the first row only.</p>
		
		<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
			<fieldset class="adminform">
				
				<table class="adminlist table table-striped">
					<tbody>
                        <tr>
                            <td width="20%" class="key">
                                <label for="upload">
                                    Upload
                                </label>
                            </td>
                            <td width="80%">
                                <input type="file" name="upload" id="inputUpload" class="inputbox" required="required">
                            </td>
                    	</tr>
                        <tr>
                            <td width="20%" class="key">
                            </td>
                            <td width="80%">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </td>
                    	</tr>
					</tbody>
				</table>
			</fieldset>

			<input type="hidden" name="option" value="com_fl_items" />
			<input type="hidden" name="item_category_id" value="<?php echo $categoryId; ?>" />
			<input type="hidden" name="task" value="uploadCSV" />
		<?php echo JHTML::_('form.token'); ?>
		</form>

		<?php
	}

	function uploadMap($csvTitles, $propSelect, $categoryId)
	{ ?>
		<h2>Map Fields</h2>
		<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
			<table class="adminlist table table-striped">
				<thead>
					<th>CSV Column</th>
					<th>Item Property</th>
				</thead>
				<tbody>
					<?php 
					$c = 0;
					foreach($csvTitles as $t) { 
					?>
	                    <tr>
	                        <td width="20%" class="key">
	                        	<?php echo $t; ?>
	                        </td>
	                        <td width="80%">
	                            <?php echo str_replace("###", $c, $propSelect); ?>
	                        </td>
	                	</tr>
						<?php 
						$c++;
					} ?>
					<tr>
                        <td width="20%" class="key">
                        </td>
                        <td width="80%">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </td>
                	</tr>
				</tbody>
			</table>
			<input type="hidden" name="option" value="com_fl_items" />
			<input type="hidden" name="item_category_id" value="<?php echo $categoryId; ?>" />
			<input type="hidden" name="task" value="uploadCSV" />
			<input type="hidden" name="mapped" value="1" />
			<?php echo JHTML::_('form.token'); ?>
		</form>
	<?php }
}