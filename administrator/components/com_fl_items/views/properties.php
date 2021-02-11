<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class FLItemsViewProperties
{
	function setPropertiesToolbar()
	{
		JToolBarHelper::title( JText::_( 'COM_FL_ITEMS') . " - Properties", 'generic.png' );
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::deleteList();
		JToolBarHelper::editList();
		JToolBarHelper::addNew('add');
		JToolBarHelper::preferences('com_fl_items', '200');
	}
	function setPropertiesSelectToolbar()
	{
		JToolBarHelper::title( JText::_( 'COM_FL_ITEMS') . " - Properties", 'generic.png' );
		JToolBarHelper::preferences('com_fl_items', '200');
	}
	
	function categorySelect($rows) {
		FLItemsViewProperties::setPropertiesSelectToolbar();
		$user =& JFactory::getUser();
		JHTML::_('behavior.tooltip');
		?>
		<h3>Select a <?php print JText::_( 'FL_ITEMS_CATEGORY' ); ?>:</h3>
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
			<tbody>
			<?php
			for ($i=0, $n=count( $rows ); $i < $n; $i++) {
				$row = &$rows[$i];
				$url = "?option=com_fl_items&view=properties&categoryId=" . $row->item_category_id;
				if($row->isHeader) {
					print "<tr><td><strong>- " . $row->name ." -</strong></td></tr>";
				} else {
					print "<tr><td><a href='$url'>" . $row->name ."</a></td></tr>";
				}
			}
			?>
			</tbody>
			</table>
		<?php
	}

	function properties( &$rows, &$pageNav, &$lists )
	{
		FLItemsViewProperties::setPropertiesToolbar();
		$user =& JFactory::getUser();
		JHTML::_('behavior.tooltip');
		?>
		<form action="index.php?option=com_fl_items&view=properties" class="form-inline" method="post" name="adminForm" id="adminForm">
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
					    <?php echo JHtml::_('searchtools.sort', '', 'p.ordering', @$lists['order_Dir'], @$lists['order'], null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
					</th>
					<th width="20">
						<?php echo JHtml::_('grid.checkall'); ?>
					</th>
					<th nowrap="nowrap" class="title">
						Name
					</th>
					<th nowrap="nowrap" class="title">
						Caption
					</th>
					<th width="5%" nowrap="nowrap"> 
						Published
					</th>
					<th width="1%" nowrap="nowrap">
						ID
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
			$ordering = (@$lists['order'] == 'p.ordering');
			$saveOrder = (@$lists['order'] == 'p.ordering');
			for ($i=0, $n=count( $rows ); $i < $n; $i++) {
				$row = &$rows[$i];

				$row->id	= $row->item_property_id;
				$link		= JRoute::_( 'index.php?option=com_fl_items&view=properties&task=edit&item_property_id[]='. $row->item_property_id );

				$row->published = $row->enableProperty;
				$published		= JHTML::_('grid.published', $row, $i );
				$checked		= JHTML::_('grid.id', $i, $row->id , '', 'item_property_id');
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td class="order nowrap center hidden-phone">
				        <?php
				            $iconClass = '';
				            if (!$saveOrder) {
				                $iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED') . '"';
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
								echo $row->name;
								?>
								
							</a>
							<?php
						}
						?>
						</span>
					</td>

					<td align="center">
						<?php echo $row->caption;?>
					</td>
					<td align="center">
						<?php echo $published;?>
					</td>
					<td align="center">
						<?php echo $row->item_property_id; ?>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
			</tbody>
			</table>

		<input type="hidden" name="item_category_id" value="<?php echo $lists['categoryId'];; ?>" />
		<input type="hidden" name="view" value="properties" />
		<input type="hidden" name="option" value="com_fl_items" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir']; ?>" />
		<?php echo JHTML::_( 'form.token' ); ?>
		</form>
		<?php
	}

	function setOnePropertiesToolbar()
	{
		$task = JRequest::getVar( 'task', '', 'method', 'string');

		JToolBarHelper::title( $task == 'add' ? JText::_( 'Items' ) . ': <small><small>[ '. JText::_( 'New' ) .' ]</small></small>' : JText::_( 'Items' ) . ': <small><small>[ '. JText::_( 'Edit' ) .' ]</small></small>', 'generic.png' );
		JToolBarHelper::save( 'save' );
		JToolBarHelper::apply('apply');
		JToolBarHelper::cancel( 'cancel' );
	}

	function property( &$row, &$lists )
	{
        jimport('joomla.application.component.helper');
    	$params = JComponentHelper::getParams('com_fl_items');
		$editor=& JFactory::getEditor();
 
		FLItemsViewProperties::setOnePropertiesToolbar();
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
					<div class="span6">
						<fieldset class="adminform">
							<legend><?php echo JText::_( 'Details' ); ?></legend>
			
							<table class="adminlist table table-striped">
							<tbody>
								<tr>
									<td width="20%" class="key">
										<label for="name">
											<?php echo JText::_( 'Property Name' ); ?>:
										</label>
									</td>
									<td width="80%">
										<input class="inputbox" type="text" name="name" id="name" size="50" value="<?php echo $row->name;?>" />
									</td>
								</tr>
								<tr>
									<td width="20%" class="key">
										<label for="caption">
											<?php echo JText::_( 'Property Caption' ); ?>:
										</label>
									</td>
									<td width="80%">
										<input class="inputbox" type="text" name="caption" id="caption" size="50" value="<?php echo $row->caption;?>" />
									</td>
								</tr>
								<tr>
									<td width="20%" class="key">
										<label for="type">
											<?php echo JText::_( 'Property Type' ); ?>:
										</label>
									</td>
									<td width="80%">
										<select type="type" name="type" id="type">
											<option value="text" <?php echo $row->type == "text" ? "selected='selected'" : "";?>>Text</option>
											<option value="textmultiple" <?php echo $row->type == "textmultiple" ? "selected='selected'" : "";?>>Text - Multiple</option>
											<option value="textarea" <?php echo $row->type == "textarea" ? "selected='selected'" : "";?>>Text Area</option>
											<option value="internalLink" <?php echo $row->type == "internalLink" ? "selected='selected'" : "";?>>Link (Internal)</option>
											<option value="externalLink" <?php echo $row->type == "externalLink" ? "selected='selected'" : "";?>>Link (External)</option>
											<option value="wysiwyg" <?php echo $row->type == "wysiwyg" ? "selected='selected'" : "";?>>WYSIWYG</option>
											<option value="number" <?php echo $row->type == "number" ? "selected='selected'" : "";?>>Number</option>
											<option value="price" <?php echo $row->type == "price" ? "selected='selected'" : "";?>>Price</option>
											<option value="date" <?php echo $row->type == "date" ? "selected='selected'" : "";?>>Date</option>
                                            <option value="multi" <?php echo $row->type == "multi" ? "selected='selected'" : "";?>>Multi-Select</option>
                                            <option value="select" <?php echo $row->type == "select" ? "selected='selected'" : "";?>>Single Select</option>
                                            <option value="image" <?php echo $row->type == "image" ? "selected='selected'" : "";?>>Image Upload</option>
                                            <option value="file" <?php echo $row->type == "file" ? "selected='selected'" : "";?>>File Upload</option>
                                            <option value="glyphicon" <?php echo $row->type == "glyphicon" ? "selected='selected'" : "";?>>Glyphicon</option>
                                            <option value="youtube" <?php echo $row->type == "youtube" ? "selected='selected'" : "";?>>YouTube Video</option>
                                            <option value="googleembedlink" <?php echo $row->type == "googleembedlink" ? "selected='selected'" : "";?>>Google Maps Embed Link</option>
                                            <option value="heading" <?php echo $row->type == "heading" ? "selected='selected'" : "";?>>Section Heading</option>
                                            <?php
                                            	foreach($lists['types'] as $type) {
                                            		$selected = "";
													if($row->type == "link-".$type->item_category_id) {
														$selected = "selected='selected'";
													}
                                            		print '<option value="link-' . $type->item_category_id . '" ' . $selected . '>' . $type->name . ' (link - Single)</option>';
                                            		$selected = "";
													if($row->type == "mlink-".$type->item_category_id) {
														$selected = "selected='selected'";
													}
                                            		print '<option value="mlink-' . $type->item_category_id . '" ' . $selected . '>' . $type->name . ' (link - Multiple)</option>';
                                            	}
												// Custom fields
												$directory = JPATH_ADMINISTRATOR . '/components/com_fl_items/helpers/customFields';
											    if (!is_dir($directory)) {
											        echo 'Invalid diretory path';
											    } else {
												    $files = array();
												
												    foreach (scandir($directory) as $customType) {
												        if ('.' === $customType) continue;
												        if ('..' === $customType) continue;
												        if ('index.html' === $customType) continue;
												        
														$customType = str_replace(".php", "", $customType);
														$customType = str_replace("fliField", "", $customType);
														$customName = $customType;
														$customType = strtolower($customName);
												
												        echo "<option value='$customType' ". ($row->type == $customType ? "selected='selected'" : '') . ">$customName (custom)</option>";
												    }
												
												    var_dump($files);
												}
                                            ?>
										</select>
									</td>
								</tr>
								
								<tr id="options" style="display: none;">
									<td width="20%" class="key">
										<label for="ordering">
											<?php echo JText::_( 'Options' ); ?>:
										</label>
										<input type="hidden" id="submit-options" name="options" />
									</td>
									<td width="80%" id="options-detail">
										
									</td>
								</tr>
								<tr id="dimensions" style="display: none;">
									<td width="20%" class="key">
										<label for="ordering">
											<?php echo JText::_( 'Image Dimensions' ); ?>:
										</label>
									</td>
									<td width="80%" id="options-detail">
										<?php
										$dims = $row->dimensions;
										if(strpos($dims, ",")) {
											$split = explode(",",$dims);
											if(count($split) == 2) {
												$dimX = $split[0];
												$dimY = $split[1];
											}
										}
										?>
										<input class="inputbox" placeholder="250" type="text" name="dimX" id="dimX" size="20" value="<?php echo $dimX;?>" /> x 
										<input class="inputbox" placeholder="250" type="text" name="dimY" id="dimY" size="20" value="<?php echo $dimY;?>" />
									</td>
								</tr>
								
								<tr>
									<td class="key">
										<?php echo JText::_( 'Published' ); ?>:
									</td>
									<td>
										<?php echo $lists['enableProperty']; ?>
									</td>
								</tr>
								
								<tr>
									<td class="key">
										<?php echo JText::_( 'Show In Directory' ); ?>:
									</td>
									<td>
										<?php echo $lists['showInDirectory']; ?>
									</td>
								</tr>
								<tr>
									<td class="key">
										<?php echo JText::_( 'Is Searchable?' ); ?>:
									</td>
									<td>
										<?php echo $lists['isSearchable']; ?>
									</td>
								</tr>
								<tr>
									<td class="key">
										<?php echo JText::_( 'Allow Front-end Users to Edit?' ); ?>:
									</td>
									<td>
										<?php echo $lists['allowUserEdit']; ?>
									</td>
								</tr>
								<tr>
									<td class="key">
										<?php echo JText::_( 'Include on user submit form?' ); ?>:
									</td>
									<td>
										<?php echo $lists['includeOnForm']; ?>
									</td>
								</tr>
								<tr style="display: none;">
									<td width="20%" class="key">
										<label for="ordering">
											<?php echo JText::_( 'Order' ); ?>:
										</label>
									</td>
									<td width="80%">
										<input class="inputbox" type="text" name="ordering" id="ordering" size="50" value="<?php echo $row->ordering;?>" />
									</td>
								</tr>
								
			
							</tbody>
							</table>
						</fieldset>
					</div>
				</div>
				
				<script>
					<?php 
					print "var options = " . json_encode($lists['options']) . ";";
					print "var type = '$row->type';";
					?>
				</script>
				<script>
				function buildOption(type, options) {
					var html = "";
					var listStr = "";
					if(type == "multi" || type == "select") {
						jQuery("#dimensions").hide();
						jQuery("#options").show();
						for(var i = 0 ; i < options.length; i++ ){
							var opt = options[i];
							if(opt.length > 0) {
								listStr += "," + opt;
								html += "<div class='option'><span data-option='" + opt + "' class='remove-option btn btn-mini'>X</span> " + opt + "</div>";
							}
						}
						html += "<div class='new-option'><input placeholder='Add Option' type='text' id='new-option' /><a href='javascript:;' class='btn btn-primary new-option-button'>Go</a></div>";
					} else if(type == "image") {
						jQuery("#options").hide();
						jQuery("#dimensions").show();
					} else {
						jQuery("#options").hide();
						jQuery("#dimensions").hide();
					}
					
					jQuery("#submit-options").val(listStr.substring(1));
					jQuery("#options-detail").html(html);
					jQuery("#new-option").focus();
				};
				
				function removeOption(remove, options) {
					var index = options.indexOf(remove);
					if (index >= 0) {
					  	options.splice( index, 1 );
					}
					buildOption(jQuery("#type").val(), options);
				}
				
				jQuery(document).ready(function() {
					buildOption("<?php print $row->type; ?>", options);
				});
				
				jQuery("#type").change(function() {
					buildOption(jQuery(this).val(), options);
				});
				
				jQuery("#options-detail").on("click", ".new-option-button", function() {
					var newVal = jQuery("#new-option").val();
					var index = options.indexOf(newVal);
					if (index == -1) {
						options.push(newVal);
						buildOption(jQuery("#type").val(), options);
					}
				});
				
				jQuery("#options-detail").on("keypress", "#new-option", function(e) {
					if(e.which == 13) {
						var newVal = jQuery("#new-option").val();
						var index = options.indexOf(newVal);
						if (index == -1) {
							options.push(newVal);
							buildOption(jQuery("#type").val(), options);
						}
					}
				});
				
				jQuery("#options-detail").on("click", ".remove-option", function() {
					var removeVal = jQuery(this).data('option');
					removeOption(removeVal, options);
				});
				
				
				</script>

			<input type="hidden" name="item_category_id" value="<?php echo $row->item_category_id; ?>" />
			<input type="hidden" name="view" value="properties" />
			<input type="hidden" name="option" value="com_fl_items" />
			<input type="hidden" name="item_property_id" value="<?php echo $row->item_property_id; ?>" />
			<input type="hidden" name="task" value="" />
		<?php echo JHTML::_( 'form.token' ); ?>
		</form>
		<?php
	}
}
