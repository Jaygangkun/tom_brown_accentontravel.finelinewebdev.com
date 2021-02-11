<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class FLItemsViewLazy
{
	function setCategoryToolbar()
	{
		JToolBarHelper::title( JText::_( 'COM_FL_ITEMS') . " - " . JText::_( 'Lazy'), 'generic.png' );
        JToolBarHelper::custom( 'create', 'generic.png', 'generic.png', 'Build Item Types', false, false );
		// JToolBarHelper::preferences('com_fl_items', '200');
	}

	function display( &$rows, &$pageNav, &$lists )
	{
		FLItemsViewLazy::setCategoryToolbar();
		$user =& JFactory::getUser();
		JHTML::_('behavior.tooltip');
		?>
		<style>
			.cat-row {
			    background: #f6f6f6;
				padding: 15px;
				border: 1px solid #ddd;
				margin-bottom: 15px;
			}
			.cat-row .well {
				border: 1px solid #ccc;
				padding: 10px;
				margin-bottom: 0;
			}
			.cat-row .prop-row {
				margin-bottom: 5px;
			}
			.btn-add-prop {
				margin-bottom: 8px;
			}
			.cat-table .table {
				margin-bottom: 0;
			}
		</style>

		<?php
		$oneClickers = array(
			array("Services", "2", array(
				array("Short Description", "textarea"),
				array("Description", "wysiwyg"),
			)),
			array("Meet the Team", "1", array(
				array("Title", "text"),
				array("Email", "text"),
				array("Bio", "textarea"),
			)),
			array("Galleries", "2", array(
				array("Description", "textarea"),
			)),
			array("FAQs", "0", array(
				array("Answer", "textarea"),
			)),
			array("Testimonials", "0", array(
				array("Quote", "textarea"),
			)),
			array("Menu", "0", array(
				array("Description", "textarea"),
				array("Price", "price"),
			)),
			array("Events", "1", array(
				array("Description", "textarea"),
				array("Date", "date"),
				array("Time", "text"),
				array("Location", "textarea"),
			)),
			array("News", "1", array(
				array("Date", "date"),
				array("Article", "wysiwyg"),
			)),
			array("Homepage Call-Outs", "1", array(
				array("Link", "internalLink"),
				array("Short Description", "textarea"),
			)),
		);
		sort($oneClickers);
		?>

		<h3>One-Click Templates:</h3>
		<div class="well">
			<?php
				foreach($oneClickers as $oc) {
					echo "
					<div style='display: inline-block; padding-right: 15px;'>
						<a href='#' class='btn btn-primary btn-one-click' data-img='$oc[1]' data-name='$oc[0]' data-fields='".json_encode($oc[2])."'>
							$oc[0]
						</a>
					</div>
					";
				}
			?>
		</div>
		
		<form action="index.php?option=com_fl_items&view=lazy" class="form-inline" method="post" name="adminForm" id="adminForm">
			<h3>Quick Add:</h3>
			<div class="cat-table">
				<div class="cat-row">
					<table class="table">
						<tr>
							<td width="10%">
								<strong>New Type:</strong>
							</td>
							<td>
								<input class="new-item-type" type="text" placeholder="New Item Type"> <a href="javascript:;" class="btn btn-danger btn-small btn-remove-cat pull-right" style="font-weight: bold;" tabIndex="-1">X</a>
							</td>
						</tr>
						<tr>
							<td width="10%">
								<strong>Images:</strong>
							</td>
							<td>
								<select class="new-item-images">
									<option value="1">One Image</option>
									<option value="2">Multiple Images</option>
									<option value="0">No Images</option>
								</select>
							</td>
						</tr>
						<tr>
							<td width="10%">
								<strong>Properties:</strong>
							</td>
							<td>
								<a href="javascript:;" class="btn btn-success btn-add-prop btn-small">+ Add Property</a><br>
								<div class="well" style="display: inline-block;">
									<div class="prop-list">
										<div class="prop-row">
											<input class="new-prop" type="text" placeholder="Caption">  
											<select class="new-type">
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
			                                    <option value="heading" <?php echo $row->type == "heading" ? "selected='selected'" : "";?>>Section Heading</option>
			                                    <?php
			                                    	foreach($lists['types'] as $type) {
			                                    		$selected = "";
														if($row->type == "link-".$type->item_category_id) {
															$selected = "selected='selected'";
														}
			                                    		print '<option value="link-' . $type->item_category_id . '" ' . $selected . '>' . $type->name . ' (link - Single)</option>';
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
											<a href="javascript:;" class="btn btn-danger btn-small btn-remove-prop" tabIndex="-1">-</a>
										</div>
									</div>
								</div>
							</td>
						</tr>
					</table>
				</div>
			</div>
			
			<a href="javascript:;" class="btn btn-success btn-add-cat">+ Add New Type</a>
			
			<input type="hidden" name="all-dat-data" class="all-data">
			
			<input type="hidden" name="view" value="lazy" />
			<input type="hidden" name="option" value="com_fl_items" />
			<input type="hidden" name="task" id="task" value="create" />
			<?php echo JHTML::_( 'form.token' ); ?>
		</form>
		
		<script>
			var newRow = jQuery(".cat-row").last().clone();
			var newProp = jQuery(".prop-row").last().clone();
			
			jQuery(".btn-add-cat").click(function() {
				jQuery(".cat-table").append(newRow);
				newRow = jQuery(".cat-row").last().clone();
				jQuery(".cat-row").last().find(".new-item-type").focus();
			});
			
			jQuery(".cat-table").on("click", ".btn-remove-cat", function() {
				jQuery(this).parents(".cat-row").remove();
			});
			
			jQuery(".cat-table").on("click", ".btn-add-prop", function() {
				jQuery(this).next().next().find(".prop-list").append(newProp);
				newProp = jQuery(".prop-row").last().clone();
			});
			
			jQuery(".cat-table").on("click", ".btn-remove-prop", function() {
				if(jQuery(this).parent().parent().find(".prop-row").length > 1) {
					jQuery(this).parent().remove();
					newProp = jQuery(".prop-row").last().clone();
				} else {
					jQuery(this).prev().val("text");
					jQuery(this).prev().prev().val("");
				}
			});
			
			jQuery("#adminForm").submit(function(e) {
				var newVals = new Array();
				var valId = 1;
                jQuery(".new-item-type").each(function() {
                    var name = jQuery(this).val();
                    var imgs = jQuery(this).parents(".cat-row").find(".new-item-images").val();
                    var newProps = new Array();
                    jQuery(this).parents(".cat-row").find(".prop-row").each(function() {
                    	var caption = jQuery(this).find(".new-prop").val();
                    	var type = jQuery(this).find(".new-type").val();
                    	newProps.push(new Array(caption, type));
                    });
                    newVals.push(new Array(name, newProps, imgs));
                });
                jQuery(".all-data").val(JSON.stringify(newVals));
            });

            jQuery(".btn-one-click").click(function() {
            	if(jQuery(".cat-row").length === 1) {
            		if(!jQuery(".new-item-type").val()) {
						jQuery(".cat-row").remove();
            		}
            	}
            	jQuery(".btn-add-cat").click();

            	var quickName = jQuery(this).attr("data-name");
            	jQuery(".new-item-type").last().val(quickName);

            	var quickImg = jQuery(this).attr("data-img");
            	jQuery(".new-item-images").last().val(quickImg);

            	var quickFields = JSON.parse(jQuery(this).attr("data-fields"));
            	for(var i = 0; i < quickFields.length ; i++ ){
            		var thisProp = quickFields[i];
            		var caption = thisProp[0];
            		var type = thisProp[1];
            		if(i > 0) {
            			jQuery(".btn-add-prop").last().click();
            		}
            		jQuery(".new-prop").last().val(caption);
            		jQuery(".new-type").last().val(type);
            	}
            });
		</script>
		<?php
	}
}
