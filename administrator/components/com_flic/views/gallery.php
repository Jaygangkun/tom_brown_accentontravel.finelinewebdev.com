<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class FLICViewGallery
{
	function setGalleriesToolbar()
	{
		JToolBarHelper::title( JText::_( 'FLIC Banner Manager' ), 'generic.png' );
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::deleteList();
		JToolBarHelper::editList();
		JToolBarHelper::addNew('add');
		// JToolBarHelper::preferences('com_flic', '200');
	}

	function galleries( &$rows, &$pageNav, &$lists )
	{
		FLICViewGallery::setGalleriesToolbar();
		$user =& JFactory::getUser();
		JHTML::_('behavior.tooltip');
		?>
		<form action="index.php?option=com_flic" class="form-inline" method="post" name="adminForm" id="adminForm">
		<table>
		<tr>
			<td align="left" width="100%">&nbsp;</td>
			<?php 
			$user = JFactory::getUser();
			$isroot = $user->authorise('core.admin');
			if($isroot) { ?>
				<td nowrap="nowrap">
					<a class="btn btn-danger" onclick="if(window.confirm('Are you sure? This can damage your galleries.')) Joomla.submitbutton('import')">Import from FL Gallery</a>
				</td>
			<?php } ?>
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
					<?php/*<th width="20">
						<?php echo JText::_( '#' ); ?>
					</th>*/?>
					<th width="20">
						<input type="checkbox" name="toggle" value=""  onclick="checkAll(<?php echo count( $rows ); ?>);" />
					</th>
					<th nowrap="nowrap" class="title">
						<?php echo JHTML::_('grid.sort',   'Name', 'g.name', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<?php/*<th nowrap="nowrap">
						<?php echo JTEXT::_('Category'); ?>
					</th>*/?>
					<th nowrap="nowrap">
						<?php echo JHTML::_('grid.sort',   '# of Images', 'countOfImages', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<?php/*<th nowrap="nowrap">
						<?php echo JHTML::_('grid.sort',   'Default Banner', 'g.isFeatured', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="85">
						<?php echo JHTML::_('grid.sort',   'Ordering', 'g.ordering', @$lists['order_Dir'], @$lists['order'] ); ?>
						<?php echo JHTML::_('grid.order',  $rows ); ?>        
					</th>*/?>
					<th width="5%" nowrap="nowrap"> 
						<?php echo JHTML::_('grid.sort',   'Published', 'g.showGallery', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="1%" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort',   'ID', 'g.flic_id', @$lists['order_Dir'], @$lists['order'] ); ?>
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

				$row->id	= $row->Gallery_id;
				$link		= JRoute::_( 'index.php?option=com_flic&c=Gallery&task=edit&flic_id[]='. $row->flic_id );

				$row->published = $row->showGallery;
				$published		= JHTML::_('grid.published', $row, $i );
				$checked		= JHTML::_('grid.id', $i, $row->flic_id , '', 'flic_id');
				?>
				<tr class="<?php echo "row$k"; ?>">
					<?php/*<td align="center">
						<?php echo $pageNav->getRowOffset($i); ?>
					</td>*/?>
					<td align="center">
						<?php echo $checked; ?>
					</td>
					<td>
						<span>
							<a href="<?php echo $link; ?>">
								<?php echo $row->name;?>
							</a>
							<?php
							if($row->isFeatured == 1) {
								echo '<span class="icon-featured" title="Default Banner"></span> ';
							}
							?>
						</span>
					</td>
					<?php/*<td>
						<?php
							echo $lists['currentCategories'][$row->flic_id];
						?>
					</td>*/?>
					<td>
						<?php
							echo $row->countOfImages;
						?>
					</td>
					<?php/*<td>
						<?php
						if($row->isFeatured == 1) {
							echo 'Yes';
						} else {
							echo 'No';
						}
						?>
					</td>
					<td class="order">
						<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
						<input type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" 
						<?php echo $disabled ?> class="text_area" style="text-align: center" />
					</td>*/?>


					<td align="center">
						<?php echo $published;?>
					</td>
					<td align="center">
						<?php echo $row->flic_id; ?>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
			</tbody>
			</table>

		<input type="hidden" name="c" value="Gallery" />
		<input type="hidden" name="option" value="com_flic" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir']; ?>" />
		<?php echo JHTML::_( 'form.token' ); ?>
		</form>
		<?php
	}

	function setGalleryToolbar($galleryName = "")
	{
		$task = JRequest::getVar( 'task', '', 'method', 'string');

		JToolBarHelper::title( $task == 'add' ? JText::_( 'Banner' ) . ': <small><small>[ '. JText::_( 'New' ) .' ]</small></small>' : JText::_( 'Edit Banner' ) . ': <small><small> ' . $galleryName . '</small></small>', 'generic.png' );
		JToolBarHelper::save( 'save' );
		JToolBarHelper::apply('apply'); 
		JToolBarHelper::cancel( 'cancel' );
	}

	function Gallery( &$row, &$lists )
	{
        jimport('joomla.application.component.helper');
    	$params = JComponentHelper::getParams('com_flic');
		$editor=& JFactory::getEditor();
		
		$user = JFactory::getUser();
		$isroot = $user->authorise('core.admin');
 
		FLICViewGallery::setGalleryToolbar($row->name);
		JRequest::setVar( 'hidemainmenu', 1 );
		
		$doc = JFactory::getDocument();
		JHtml::_('jquery.framework');
		$doc->addScript('/administrator/components/com_flic/assets/selectize.js');
		$doc->addStyleSheet('/administrator/components/com_flic/assets/selectize.bootstrap2.css');
		$doc->addScript('https://code.jquery.com/ui/1.12.1/jquery-ui.js');
		$doc->addStyleSheet('//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
		
		JLoader::register('MenusHelper', JPATH_ADMINISTRATOR . '/components/com_menus/helpers/menus.php');
		$menuTypes = MenusHelper::getMenuLinks();
		JHtml::_('script', 'jui/treeselectmenu.jquery.min.js', array('version' => 'auto', 'relative' => true));
		
		$selectedMenuItems = explode(",", $row->menuItems);
		
		$positionOptions[] = (object) array("value" => 1, "text" => "Top Left");
		$positionOptions[] = (object) array("value" => 2, "text" => "Top Center");
		$positionOptions[] = (object) array("value" => 3, "text" => "Top Right");
		$positionOptions[] = (object) array("value" => 7, "text" => "Middle Left");
		$positionOptions[] = (object) array("value" => 8, "text" => "Middle Center");
		$positionOptions[] = (object) array("value" => 9, "text" => "Middle Right");
		$positionOptions[] = (object) array("value" => 4, "text" => "Bottom Left");
		$positionOptions[] = (object) array("value" => 5, "text" => "Bottom Center");
		$positionOptions[] = (object) array("value" => 6, "text" => "Bottom Right");
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

	  	<style>
		  	#sortable { list-style-type: none; margin: 0; padding: 0; }
		  	#sortable li { margin: 3px 3px 3px 0; padding: 1px; float: left; width: 150px; height: 150px; font-size: 4em; text-align: center; position: relative;}
		  	#sortable li.active-0 { background: #CCC; }
		  	#sortable li.active-0 > img { opacity: 0.6; }
		  	#sortable li.delete { background: #ecc; }
		  	#sortable li.delete > img { opacity: 0.5; }
			.edit-fl_gallery table.fl_gallery-images td {vertical-align:top; text-align:left !important; padding:5px 3px; border-top:1px solid #eee;}
			.btn.btn-primary.active {background-color: #185b91;}
			.edit-button {
			    z-index: 9;
			    position: absolute;
			    width: 100%;
			}
			.edit-button img {
			    width: 20px;
			    cursor: pointer;
			}
			.edit-pop, .manage-menu {
				display: none;
				position: fixed;
				top: 0;
				left: 0;
				width: calc(100% - 20vw);
				height: 100%;
				background: rgba(0,0,0,0.75);
				z-index: 999;
				padding: 60px 10vw;
			}
			.edit-pop-inner {
			}
			.edit-pop-img-wrapper {
				margin-bottom: 15px;
			}
			.edit-pop-img-wrapper img {
				border: 7px solid #888;
			}
			.form-group {
				margin-bottom: 10px;
			}
			.edit-pop label {
				font-weight: bold;
			}
			.edit-pop-buttons {
				padding: 15px;
				text-align: right;
			}
			.edit-pop-body {
				padding: 15px 30px;
			}
			.modal-body {
				overflow-y: auto;
				max-height: calc(100vh - 240px);
			}
			#pop-img {
				max-width: calc(100% - 14px); max-height: 150px;
			}
	  	</style>

		<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">

				<div class="row-fluid">
					<div class="span6">
						<fieldset class="adminform">
							<legend><?php echo JText::_( 'Details' ); ?></legend>
			
							<table class="adminlist table table-striped">
							<tbody>
								<tr>
									<td width="20%" class="key">
										<label for="name">
											<?php echo JText::_( 'Gallery Name' ); ?>:
										</label>
									</td>
									<td width="80%">
										<input class="inputbox" type="text" name="name" id="name" size="50" value="<?php echo $row->name;?>" />
									</td>
								</tr>
								<?php/*<tr>
									<td width="20%" class="key">
										<label for="alias">
											<?php echo JText::_( 'Alias' ); ?>:
										</label>
									</td>
									<td width="80%">
										<input class="inputbox" type="text" name="alias" id="alias" size="50" value="<?php echo $row->alias;?>" />
									</td>
								</tr>
								<tr>
									<td width="20%" class="key">
										<label for="Gallery_category_id">
											<?php echo JText::_( 'Category' ); ?>:
										</label>
									</td>
									<td width="80%">
										<? echo $lists['GalleryCategory']; ?>
										<script>
										jQuery('.selectize').selectize({
											maxItems: 1
										});
										</script>
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
								<tr>
									<td width="20%" class="key">
										<label for="price">
											<?php echo JText::_( 'Description' ); ?>:
										</label>
									</td>
									<td width="80%">
										<? echo $editor->display( 'description',  $row->description, '100%', '300', '75', '20' ) ; ?>
									</td>
								</tr>
								<tr>
									<td width="20%" class="key">
										<label for="resizeImageWidth">
											<?php echo JText::_( 'Image Resize Dimensions (W x H)' ); ?>:
										</label>
									</td>
									<td width="80%">
										<input class="inputbox" placeholder="Width" type="text" name="resizeImageWidth" id="resizeImageWidth" size="25" value="<?php echo $row->resizeImageWidth;?>" /> X 
										<input class="inputbox" placeholder="Height" type="text" name="resizeImageHeight" id="resizeImageHeight" size="25" value="<?php echo $row->resizeImageHeight;?>" />
									</td>
								</tr>
								<tr>
									<td width="20%" class="key">
										<label for="ordering">
											<?php echo JText::_( 'Ordering' ); ?>:
										</label>
									</td>
									<td width="80%">
										<input class="inputbox" type="text" name="ordering" id="ordering" size="50" value="<?php echo $row->ordering;?>" />
									</td>
								</tr>*/?>
								<tr>
									<td class="key">
										<?php echo JText::_( 'Published' ); ?>:
									</td>
									<td>
										<?php 
										if($row->isFeatured) {
											echo "<strong>Yes</strong>";
											echo "<input type='hidden' name='showGallery' value='1'>";
										} else {
											echo $lists['showGallery'];
										}
										?>
									</td>
								</tr>
								<tr>
									<td class="key">
										Default Banner:
									</td>
									<td>
										<?php 
										if($row->isFeatured) {
											echo "<strong>Yes</strong>";
											echo "<input type='hidden' name='isFeatured' value='1'>";
										} else {
											echo $lists['isFeatured'];
										}?>
									</td>
								</tr>
								<tr>
									<td class="key">
										Pages to Display On:
									</td>
									<td>
										<div class='manage-pages-btns <?php if($row->isFeatured) { echo 'hidden'; }?>'>
											<a href="javascript:;" class="btn btn-primary btn-manage-pages">Manage Pages</a>
										</div>
										<div class='manage-pages-msg <?php if(!$row->isFeatured) { echo 'hidden'; }?>'>
											<strong>Default Banner</strong>&nbsp;&nbsp;&nbsp;<small>(all other pages)</small>
										</div>
										<script>
											jQuery('input[name=isFeatured]').change(function() {
												if(this.value == 1) {
													jQuery(".manage-pages-btns").addClass("hidden");
													jQuery(".manage-pages-msg").removeClass("hidden");
												} else {
													jQuery(".manage-pages-btns").removeClass("hidden");
													jQuery(".manage-pages-msg").addClass("hidden");
												}
											});
										</script>
									</td>
								</tr>
								<?php
								if($isroot) {
									$activePositions = explode(",", $row->captionPositions);
									echo '
									<tr>
										<td class="key">
											Enable Caption Positions:<br><br>
											<em><small>Super User Only</small></em>
										</td>
										<td>';
											foreach($positionOptions as $pos) {
												echo "
												<label class='checkbox' style='display: inline-block; width: 28%;'>
											      	<input class='captionPositionToggle' type='checkbox' value='$pos->value' ". (in_array($pos->value, $activePositions) ? "checked='checked'" : "") . "> $pos->text
											    </label>";
											}
										echo '
											<input type="hidden" value="'.$row->captionPositions.'" name="captionPositions" id="captionPositions">
										</td>
									</tr>';
								}
								
								
								foreach($positionOptions as $k => $pos) {
									if(!in_array($pos->value, $activePositions)) {
										unset($positionOptions[$k]);
									}
								}
								?>
							</tbody>
						</table>
						<script>
							jQuery(".captionPositionToggle").change(function() {
								var newPositions = new Array();
								jQuery(".captionPositionToggle:checked").each(function() {
									newPositions.push(jQuery(this).val());
								});
								jQuery("#captionPositions").val(newPositions.join(","));
							});
						</script>
					</fieldset>
					<?php /*<fieldset class="table table-striped">
						<legend><?php echo JText::_( 'Meta' ); ?></legend>
		
						<table class="adminlist table table-striped">
							<tbody>
								<tr>
									<td width="20%" class="key">
										<label for="metaTitle">
											<?php echo JText::_( 'HTML Title' ); ?>:
										</label>
									</td>
									<td width="80%">
										<input class="inputbox" type="text" name="metaTitle" id="metaTitle" size="50" value="<?php echo $row->metaTitle;?>" />
									</td>
								</tr>
								<tr>
									<td width="20%" class="key">
										<label for="metaKeywords">
											<?php echo JText::_( 'Meta Keywords' ); ?>:
										</label>
									</td>
									<td width="80%">
										<input class="inputbox" type="text" name="metaKeywords" id="metaKeywords" size="50" value="<?php echo $row->metaKeywords;?>" />
									</td>
								</tr>
								<tr>
									<td width="20%" class="key">
										<label for="metaDescription">
											<?php echo JText::_( 'Meta Description' ); ?>:
										</label>
									</td>
									<td width="80%">
										<textarea class="inputbox" name="metaDescription" id="metaDescription" size="50"><?php echo $row->metaDescription;?></textarea>
									</td>
								</tr>
							</tbody>
						</table>
					</fieldset>*/?>
					
				</div>
					
				<div class="span6">
					<fieldset class="table table-striped">
						<legend><?php echo JText::_( 'Upload Images' ); ?></legend>
						
						<table class="admintable">
							<tbody>
								<tr>
									<td class="key">
										<label for="uploadZipFile">
											<?php echo JText::_( 'Zip File' ); ?>:
										</label>
									</td>
									<td width="80%">
										<input class="inputbox" type="file" name="uploadZipFile" id="uploadZipFile" />
									</td>
								</tr>
							<? 
								for($iNewImage=1; $iNewImage <= 5; $iNewImage++) {
								echo '<tr>';
									echo '<td class="key">New Image #'.$iNewImage.'</td>';
									echo '<td>';
										echo '<input class="inputbox" type="file" name="new_filename_'.$iNewImage.'" id="new_filename_'.$iNewImage.'" />';
									echo '</td>';
								echo '</tr>';
								}
							?>
							</tbody>
						</table>
					</fieldset>
				</div>
			</div>
			
			<div class="manage-menu">
				<div class="modal">
					<div class="modal-header">
						<h3>Manage Menu Items</h3>
					</div>
					<div class="modal-body">
						<div class="edit-pop-body">
							<div id="jform_menuselect" class="controls">
								<?php if (!empty($menuTypes)) : ?>
								<?php $id = 'jform_menuselect'; ?>
						
								<div class="well well-small">
									<div class="form-inline">
										<span class="small"><?php echo JText::_('JSELECT'); ?>:
											<a id="treeCheckAll" href="javascript://"><?php echo JText::_('JALL'); ?></a>,
											<a id="treeUncheckAll" href="javascript://"><?php echo JText::_('JNONE'); ?></a>
										</span>
										<span class="width-20">|</span>
										<span class="small">Expand:
											<a id="treeExpandAll" href="javascript://"><?php echo JText::_('JALL'); ?></a>,
											<a id="treeCollapseAll" href="javascript://"><?php echo JText::_('JNONE'); ?></a>
										</span>
										<input type="text" id="treeselectfilter" name="treeselectfilter" class="input-medium search-query pull-right" size="16"
											autocomplete="off" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" aria-invalid="false" tabindex="-1">
									</div>
						
									<div class="clearfix"></div>
						
									<hr class="hr-condensed" />
						
									<ul class="treeselect">
										<?php foreach ($menuTypes as &$type) : ?>
										<?php if (count($type->links)) : ?>
											<?php $prevlevel = 0; ?>
											<li>
												<div class="treeselect-item pull-left">
													<label class="pull-left nav-header"><?php echo $type->title; ?></label></div>
											<?php foreach ($type->links as $i => $link) : ?>
												<?php
												if ($prevlevel < $link->level)
												{
													echo '<ul class="treeselect-sub">';
												} elseif ($prevlevel > $link->level)
												{
													echo str_repeat('</li></ul>', $prevlevel - $link->level);
												} else {
													echo '</li>';
												}
												$selected = in_array($link->value, $selectedMenuItems);
												?>
													<li>
														<div class="treeselect-item pull-left">
															<?php
															$uselessMenuItem = in_array($link->type, array('separator', 'heading', 'alias', 'url'));
															?>
															<input type="checkbox" class="pull-left novalidate" name="menuItems[]" id="<?php echo $id . $link->value; ?>" value="<?php echo (int) $link->value; ?>"<?php echo $selected ? ' checked="checked"' : ''; echo $uselessMenuItem ? ' disabled="disabled"' : ''; ?> />
															<label for="<?php echo $id . $link->value; ?>" class="pull-left">
																<?php echo $link->text; ?> <span class="small"><?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $link->alias); ?></span>
																<?php if (JLanguageMultilang::isEnabled() && $link->language != '' && $link->language != '*') : ?>
																	<?php if ($link->language_image) : ?>
																		<?php echo JHtml::_('image', 'mod_languages/' . $link->language_image . '.gif', $link->language_title, array('title' => $link->language_title), true); ?>
																	<?php else : ?>
																		<?php echo '<span class="label" title="' . $link->language_title . '">' . $link->language_sef . '</span>'; ?>
																	<?php endif; ?>
																<?php endif; ?>
																<?php if ($link->published == 0) : ?>
																	<?php echo ' <span class="label">' . JText::_('JUNPUBLISHED') . '</span>'; ?>
																<?php endif; ?>
																<?php if ($uselessMenuItem) : ?>
																	<?php echo ' <span class="label">' . JText::_('COM_MODULES_MENU_ITEM_' . strtoupper($link->type)) . '</span>'; ?>
																<?php endif; ?>
															</label>
														</div>
														<?php
								
														if (!isset($type->links[$i + 1]))
														{
															echo str_repeat('</li></ul>', $link->level);
														}
														$prevlevel = $link->level;
														?>
														<?php endforeach; ?>
													</li>
											<?php endif; ?>
										<?php endforeach; ?>
									</ul>
									<div id="noresultsfound" style="display:none;" class="alert alert-no-items">
										<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
									</div>
									<div style="display:none;" id="treeselectmenu">
										<div class="pull-left nav-hover treeselect-menu">
											<div class="btn-group">
												<a href="#" data-toggle="dropdown" class="dropdown-toggle btn btn-micro">
													<span class="caret"></span>
												</a>
												<ul class="dropdown-menu">
													<li class="nav-header"><?php echo JText::_('COM_MODULES_SUBITEMS'); ?></li>
													<li class="divider"></li>
													<li class=""><a class="checkall" href="javascript://"><span class="icon-checkbox" aria-hidden="true"></span> <?php echo JText::_('JSELECT'); ?></a>
													</li>
													<li><a class="uncheckall" href="javascript://"><span class="icon-checkbox-unchecked" aria-hidden="true"></span> <?php echo JText::_('COM_MODULES_DESELECT'); ?></a>
													</li>
													<div class="treeselect-menu-expand">
													<li class="divider"></li>
													<li><a class="expandall" href="javascript://"><span class="icon-plus" aria-hidden="true"></span> <?php echo JText::_('COM_MODULES_EXPAND'); ?></a></li>
													<li><a class="collapseall" href="javascript://"><span class="icon-minus" aria-hidden="true"></span> <?php echo JText::_('COM_MODULES_COLLAPSE'); ?></a></li>
													</div>
												</ul>
											</div>
										</div>
									</div>
								</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<div class="modal-footer edit-pop-buttons">
						<a href="javascript:;" class="btn btn-success btn-confirm-pages">OK</a>
					</div>
				</div>
			</div>
			
			
			
			<div class="">
				<div class="edit-pop">
					<div class="modal edit-pop-inner">
						<div class="modal-header">
							<h3>Edit Image</h3>
						</div>
						<div class="modal-body">
							<div class="edit-pop-body">
								<div class="row-fluid">
									<div class="span8">
										<div class="<?php if(!count($positionOptions)) { echo "hidden"; }?>">
											<div class="">
												<label>Title & Caption:</label>
												<input placeholder="Caption Title" style="width: 95%" class="inputbox" type="text" id="pop-title" size="3" value="" />
											</div>
											<div class="clearfix">
												<?php echo $editor->display( 'pop-caption',  '', '100%', '300', '75', '20' ); ?>
											</div>
										</div>
										<?php 
										if(!count($positionOptions)) {
											echo "<br><br><em>Captions are disabled on this gallery.</em>";
										}
										?>
									</div>
									<div class="span4">
										<div class="edit-pop-img-wrapper">
											<img id="pop-img" src="">
										</div>
										<legend>Image Details</legend>
										<input id="pop-id" type="hidden" value="">
										<div class="form-group">
											<label>Publish:</label>
											<?php echo JHTML::_('select.booleanlist',  'pop-show', '', 0); ?>
										</div>
										<?php 
										if(count($positionOptions)) { ?>
											<div class="form-group">
												<label>Caption Display Position:</label>
												<?php echo JHTML::_('select.genericlist', $positionOptions, 'pop-position', 'class="inputbox" size="1"','value', 'text' );?>
											</div>
										<?php } ?>
										<div class="form-group">
											<label>URL:</label>
											<input placeholder="http://www.example.com" style="width: 95%" class="inputbox" type="text" id="pop-url" size="3" value="" />
										</div>
										<div class="form-group">
											<label>Open in New Window:</label>
											<?php echo JHTML::_('select.booleanlist', 'pop-new-window', 'class="inputbox" size="1"' ); ?>
										</div>
										<div class="form-group" style="background:#f9ecec; border:1px solid #fcc; padding: 8px;">
											<input type="checkbox" name="pop-delete" id="pop-delete" value="1" style="margin: 0;">
											<label style="display: inline-block;padding-left: 5px;font-weight: bold;" for="pop-delete">Delete Image</label>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer edit-pop-buttons">
							<a href="javascript:;" class="btn btn-close">Close</a>
							<a href="javascript:;" class="btn btn-success btn-confirm">Confirm</a>
						</div>
					</div>
				</div>
				
				<?php if(count($lists['galleryImage']) && $row->flic_id) { ?>
					<fieldset class="adminform">
						<legend><?php echo JText::_( 'Images' ); ?></legend>
						<?php /*<a href="javascript:;" class="btn btn-primary btn-img-properties">Edit Properties</a>
						<a href="javascript:;" class="btn btn-primary btn-img-ordering active">Edit Ordering</a>*/?>
						<div class='edit-pane' style="display: none;">
							<table class="table table-striped fl_gallery-images" cellpadding="0" cellspacing="0">
							<tbody>
								<tr>
									<td width="15" class="key" style="text-align:center;">Preview</td>
									<td class="key" style="text-align:center;">
										<label >
											<?php echo JText::_( 'Options' ); ?>
										</label>
									</td>
									<td width="50%" class="key" style="text-align:center;">
										<label >
											<?php echo JText::_( 'Caption Title & Description' ); ?>
										</label>
									</td>
									<!-- <td width="1" class="key" style="text-align:center;">
										<label >
											<?php echo JText::_( 'Ordering' ); ?>
										</label>
									</td> -->
									<td width="1" class="key" style="text-align:center;">
										<label >
											<?php echo JText::_( 'Published' ); ?>
										</label>
									</td>
									<td width="1" class="key" style="text-align:center; color:#C00; background:#f9ecec; border:1px solid #fcc;">
										<label >
											<?php echo JText::_( 'Delete' ); ?>
										</label>
									</td>
								</tr>
				
							<? 
								foreach( $lists['galleryImage'] AS $galleryImageRow ) {
									$id = $galleryImageRow->flic_image_id;
									echo '<tr>';
										echo '<td style="text-align: center;">';
											echo '<img id="img_'.$id.'" src="/images/flic/galleries/'.$galleryImageRow->flic_id.'/'.$galleryImageRow->filename.'" width="100" />';
											echo '<input class="inputbox" type="file" name="filename_'.$id.'" id="filename_'.$id.'" />';
										echo '</td>';
										echo '<td>';
											echo '<div>';
												echo '<label>URL:</label>';
												echo '<input placeholder="http://www.example.com" style="width: 95%" class="inputbox" type="text" name="url_'.$id.'" id="url_'.$id.'" size="3" value="'.$galleryImageRow->url.'" />';
											echo '</div>';
											echo '<div>';
												echo '<label>New Window:</label>';
												echo JHTML::_('select.booleanlist', 'newWindow_'.$id, 'class="inputbox" size="1"', $galleryImageRow->newWindow );
											echo '</div>';
											echo '<br><label>Caption Display Position:</label>';
											echo JHTML::_('select.genericlist', $positionOptions, 'messagePosition_'.$id, 'class="inputbox" size="1"','value', 'text', $galleryImageRow->messagePosition );
										echo '</td>';
										echo '<td>';
											echo '<label>Caption Title:</label>';
											echo '<input placeholder="Caption Title" style="width: 95%" class="inputbox" type="text" name="captionTitle_'.$id.'" id="captionTitle_'.$id.'" size="3" value="'.$galleryImageRow->captionTitle.'" />';
											
											echo '<label>Caption Description:</label>';
											echo '<textarea name="captionMessage_'.$id.'" id="captionMessage_'.$id.'">' . htmlspecialchars($galleryImageRow->captionMessage) . '</textarea>';
											// echo $editor->display( 'captionMessage_'.$id,  $galleryImageRow->captionMessage, '100%', '300', '75', '20' );
										echo '</td>';
										// echo '<td>';
										// 	echo '<input style="width: 35px;" class="inputbox" type="text" name="ordering_'.$id.'" id="ordering_'.$id.'" size="3" value="'.$galleryImageRow->ordering.'" />';
										// echo '</td>';
										echo '<td style="white-space:nowrap;">';
											echo JHTML::_('select.booleanlist',  'showGalleryImage_'.$id, '', $galleryImageRow->showGalleryImage);
										echo '</td>';
										echo '<td style="background:#f9ecec; border:1px solid #fcc; text-align:center;">';
											echo '<input type="checkbox" id="delete_gallery_image_'.$id.'" name="delete_gallery_image_'.$id.'" value="1" />';
										echo '</td>';
									echo '</tr>';
								}
							?>
							</tbody>
							</table>

						</div>

						<div class="reorder-pane">
							<h4>Image Reordering <small> - Click and drag images to change ordering.</small></h4>
							<div class="alert alert-info save-msg" style="display: none;">Click the <strong>Save</strong> button to confirm your changes!</div>
							<ul id="sortable" class="reorder-photos-list">
								<?php
								foreach( $lists['galleryImage'] AS $galleryImageRow ) {
									$id = $galleryImageRow->flic_image_id;
									$isActive = $galleryImageRow->showGalleryImage;
									echo "<li id='image_li_$id' data-imgid='$id' class='ui-state-default active-$isActive'>";
										echo '<div class="edit-button" data-imgid="'.$id.'"><img title="Click to Edit Details" src="/administrator/components/com_flic/assets/icon-caption-toggle.png"></div>';
										echo '<img src="/images/flic/galleries/'.$galleryImageRow->flic_id.'/'.$galleryImageRow->filename.'" style="width: 100%; height: 100%; object-fit: contain;" />';
									echo '</li>';
								}
								?>
							</ul>
							<input type="hidden" id="img-ordering" name="img-ordering" value="">
							<script>
								jQuery(document).ready(function() {
									jQuery('ul.reorder-photos-list').sortable();
									jQuery('ul.reorder-photos-list').disableSelection();

									jQuery('#adminForm').submit(function() {
										var imgOrdering = [];
										jQuery('ul.reorder-photos-list li').each(function() {
											imgOrdering.push(jQuery(this).attr('data-imgid'));
										});
										jQuery('#img-ordering').val(''+imgOrdering);
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
									jQuery(".btn-manage-pages").click(function() {
										jQuery(".manage-menu").show();
									});
									jQuery(".btn-confirm-pages").click(function() {
										jQuery(".manage-menu").hide();
									});
									jQuery(".edit-button").click(function() {
										var id = jQuery(this).attr("data-imgid");
										jQuery("#pop-id").val(id);
										jQuery("#pop-title").val(jQuery("#captionTitle_"+id).val());
										jQuery("#pop-caption").val(jQuery("#captionMessage_"+id).val());
										tinyMCE.get('pop-caption').setContent(jQuery("#captionMessage_"+id).val());
										jQuery("#pop-url").val(jQuery("#url_"+id).val());
										jQuery("input[name='pop-show']").val([jQuery("input[name='showGalleryImage_"+id+"']:checked").val()]);
										jQuery("#pop-img").attr("src", jQuery("#img_"+id).attr("src"));
										jQuery("#pop-position").val(jQuery("#messagePosition_"+id).val());
										jQuery("input[name='pop-new-window']").val([jQuery("input[name='newWindow_"+id+"']:checked").val()]);
										
										jQuery("#pop-delete").prop( "checked", false );
										if(jQuery("#delete_gallery_image_"+id).prop("checked")) {
											jQuery("#pop-delete").prop( "checked", true );
										}
										
										
										jQuery(".edit-pop").show();
									});
									jQuery(".edit-pop-buttons .btn-close").click(function() {
										jQuery(".edit-pop").hide();
									});
									jQuery(".edit-pop-buttons .btn-confirm").click(function() {
										var id = jQuery("#pop-id").val();
										jQuery("#captionMessage_"+id).val(tinyMCE.get('pop-caption').getContent());
										jQuery("#captionTitle_"+id).val(jQuery("#pop-title").val());
										jQuery("#url_"+id).val(jQuery("#pop-url").val());
										jQuery("#messagePosition_"+id).val(jQuery("#pop-position").val());
										jQuery("input[name='showGalleryImage_"+id+"']").val([jQuery("input[name='pop-show']:checked").val()]);
										jQuery("input[name='newWindow_"+id+"']").val([jQuery("input[name='pop-new-window']:checked").val()]);
										
										if(jQuery("#pop-delete").prop("checked")) {
											jQuery("#delete_gallery_image_"+id).prop( "checked", true );
											jQuery("#image_li_"+id).addClass("delete");
										} else {
											jQuery("#delete_gallery_image_"+id).prop( "checked", false );
											jQuery("#image_li_"+id).removeClass("delete");
										}
										
										if(jQuery("input[name='pop-show']:checked").val() == 1) {
											jQuery("#image_li_"+id).addClass("active-1");
											jQuery("#image_li_"+id).removeClass("active-0");
										} else {
											jQuery("#image_li_"+id).addClass("active-0");
											jQuery("#image_li_"+id).removeClass("active-1");
										}
										
										jQuery(".edit-pop").hide();
										jQuery(".save-msg").hide();
										jQuery(".save-msg").slideDown();
									});
								});

							</script>
						</div>
					</fieldset>		
				<?php } ?>
			</div>

			<input type="hidden" name="c" value="Gallery" />
			<input type="hidden" name="option" value="com_flic" />
			<input type="hidden" name="flic_id" value="<?php echo $row->flic_id; ?>" />
			<input type="hidden" name="task" value="" />
		<?php echo JHTML::_( 'form.token' ); ?>
		</form>
		<?php
	}

}
