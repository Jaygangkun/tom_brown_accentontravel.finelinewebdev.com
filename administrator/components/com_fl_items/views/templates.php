<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class FLItemsViewTemplate
{
	function setCategoryToolbar()
	{
		JToolBarHelper::title( JText::_( 'COM_FL_ITEMS') . " - " . JText::_( 'Templates'), 'generic.png' );
		JToolBarHelper::preferences('com_fl_items', '200');
	}

	function templates( &$rows, &$pageNav, &$lists )
	{
		FLItemsViewTemplate::setCategoryToolbar();
		$user =& JFactory::getUser();
		JHTML::_('behavior.tooltip');
		?>
		<form action="index.php?option=com_fl_items&view=templates" class="form-inline" method="post" name="adminForm" id="adminForm">
		<h3>Select a Template:</h3>
		<table>
			<tbody>
				<tr>
					<td width="100%" align="left">&nbsp;</td>
					<td nowrap="nowrap">
						
					</td>
				</tr>
			</tbody>
		</table>
		<table class="table table-striped">
			<?php
			foreach($rows as $row) {
				$linkResult = JRoute::_( 'index.php?option=com_fl_items&view=templates&task=editResults&categoryId='.$row->item_category_id );
				$linkDetail = JRoute::_( 'index.php?option=com_fl_items&view=templates&task=editDetail&categoryId='.$row->item_category_id );
				$linkChildren = JRoute::_( 'index.php?option=com_fl_items&view=templates&task=editChildren&categoryId='.$row->item_category_id );
				$linkLinks = JRoute::_( 'index.php?option=com_fl_items&view=templates&task=editLinks&categoryId='.$row->item_category_id );
				$linkNoResults = JRoute::_( 'index.php?option=com_fl_items&view=templates&task=editNoResults&categoryId='.$row->item_category_id );
				?>
				<tr>
					<?php
					if($row->isHeader)
					{ ?>
						<td width="20%" style="background: #EEE;">
							<strong>- <?php echo $row->name; ?> -</strong>
						</td>
						<td colspan="5">
						</td>
					<?php
					} else { ?>
						<td width="20%" style="background: #EEE;">
							<?php echo $row->name; ?>
						</td>
						<td width="1%">
							<a href="<?php echo $linkResult;?>">Results</a>
						</td>
						<td width="1%">
							<a href="<?php echo $linkDetail;?>">Detail</a>
						</td>
						<td width="1%">
							<a href="<?php echo $linkChildren;?>">Child</a>
						</td>
						<td width="1%">
							<a href="<?php echo $linkLinks;?>">Links</a>
						</td>
						<td width="100%">
							<a href="<?php echo $linkNoResults;?>">No Results</a>
						</td>
					<?php } ?>
				</tr>
			<?php } ?>
		</table>
		
		<h3>Modules</h3>
			<table class="table table-striped">
			<?php
			foreach($lists['modules'] as $mod) {
				$filename = str_replace(".php", "", str_replace("module-", "", $mod));
				$linkModule = JRoute::_( 'index.php?option=com_fl_items&view=templates&task=editModule&module='.$filename );
				?>
				<tr>
					<td width="20%" style="background: #EEE;">
						<?php echo $filename; ?>
					</td>
					<td width="100%">
						<a href="<?php echo $linkModule;?>">Edit</a>
					</td>
				</tr>
			<?php } ?>
			<tr>
				<td width="20%" style="background: #e8eaff; line-height: 28px; text-align: center;">
					<strong>New Module</strong>
				</td>
				<td width="100%" style="">
					<input type="text" class="form-control" name="newModuleName" placeholder="moduleTemplateName" style="margin: 0;"> <button class="btn btn-primary btn-new-module">Create</button>
				</td>
			</tr>
		</table>
		
		<input type="hidden" name="view" value="templates" />
		<input type="hidden" name="option" value="com_fl_items" />
		<input type="hidden" name="task" id="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir']; ?>" />
		<?php echo JHTML::_( 'form.token' ); ?>
		</form>
		
		<script>
			jQuery(".btn-new-module").click(function() {
				jQuery("#task").val("newModule");
			});
		</script>
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

	function setEditResultsToolbar()
	{
		$task = JRequest::getVar( 'task', '', 'method', 'string');

		JToolBarHelper::title( JText::_( 'Template' ) . ': <small><small>[ '. JText::_( 'Edit' ) .' - Results ]</small></small>', 'generic.png' );
		JToolBarHelper::save( 'save' );
		JToolBarHelper::apply( 'apply' );
		JToolBarHelper::cancel( 'cancel' );
	}
	
	function editTemplate( $row, $lists, $filename) {
		FLItemsViewTemplate::setEditResultsToolbar();
		
		JLoader::register('MenusHelper', JPATH_ADMINISTRATOR . '/components/com_menus/helpers/menus.php');
		$menuTypes = MenusHelper::getMenuLinks();
		
		if(strpos($filename, "module-") === 0) {
			$type = "module";
		} else {
			if(strpos($filename, "no-results.php")) {
				$type = "NoResults";
			} else {
				$split = explode("-", $filename);
				$type = $split[count($split)-1];
				$type = str_replace(".php", "", $type);
			}
		}
		
		$hasImages = $row->hasImages;
		$hasShortDescription = false;
		$hasDescription = false;
		
		$tmpCategoryName = preg_replace("/[^A-Za-z0-9 ]/", '', strtolower($row->name));
		$tmpCategoryName = str_replace(" ", "-", $tmpCategoryName);
		
		?>
		<script src="/administrator/components/com_fl_items/assets/ace/ace.js" type="text/javascript" charset="utf-8"></script>
		<style>
			#phpText {
				position: absolute;
		        top: 0;
		        right: 0;
		        bottom: 0;
		        left: 0;
		        height: 600px;
	        }
			.quick-add {padding: 4px 15px; cursor: pointer; border-bottom: 2px solid #CCC; -webkit-touch-callout: none; -webkit-user-select: none; -khtml-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; background: #FFF; margin: 4px 0 0;}
			.quick-add-close {padding: 4px 5px; cursor: pointer; border-bottom: 2px solid #CCC; -webkit-touch-callout: none; -webkit-user-select: none; -khtml-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; background: #d62f3f; color: #fff; margin: 4px 0 0; text-align: center; font-weight: bold;}
			.quick-add-close:hover {background: #c41d2d;}
			.quick-info {padding: 4px 5px; cursor: pointer; border-bottom: 2px solid #CCC; -webkit-touch-callout: none; -webkit-user-select: none; -khtml-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; background: #FFF; margin: 4px 0 0;}
			.quick-add:hover {background: #DDD;}
			.quick-add-list { max-height: 600px; overflow: auto; padding-bottom: 20px; }
			.quick-add-links { max-height: 600px; overflow: auto; padding-bottom: 20px;  display: none; }
			.quick-add-list .quick-add {text-align: center; }
			.quick-add-links .quick-add {/*text-align: center;*/ }
			.quick-add-list h3 {margin-top: 0;}
			.quick-add-list h4 {background: #CCC; margin-top: 25px; margin-bottom: 3px; padding: 5px;}
			.quick-add-list h4:nth-of-type(1) {margin-top: 0;}
			.quick-add-title {
			    background: #666;
			    color: white;
			    text-align: center;
			    padding: 2px;
			    border-radius: 8px 8px 0 0;
			    font-weight: bold;
			}
			.quick-add-wrapper {
				background: #DDD;
				margin-bottom: 8px;
				border-radius: 8px 8px 0 0;
				padding: 4px;
			}
			.quick-add-btns .quick-add {
				background: #F5F5F5;
				padding: 0;
				line-height: 25px;
			}
			.quick-add-btns .quick-add:hover {
			    background: #eee;
			}
		</style>
		<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
			<h4>Edit Template - <small><?php echo $filename;?></small></h4>
			<div class="row-fluid">
				<div class="span9" style="position: relative;">
					<div id='phpText'><?php echo htmlentities( str_replace("<!script", "<script", $lists['data']) );?></div>
					<textarea name="templateText" id="templateText" ></textarea>
				</div>
				<div class="span3">
					<div class="quick-add-links well">
						<?php
						if (!empty($menuTypes)) {
							foreach($menuTypes as $menuType) {
								if(count($menuType->links)) {
									echo "<h4>$menuType->title</h4>";
									foreach($menuType->links as $link) {
										echo "<div class='quick-add quick-add-this-link' data-temp='{url-$link->value}'>";
											for($i = 1 ; $i < $link->level; $i++) {
												echo "-- ";
											}
											echo " ".$link->text;
										echo "</div>";
									}
								}
							}
						}
						?>
						<div class="quick-add-close" data-temp="{loopstart}{loopend}">
							Cancel
						</div>
					</div>
					<div class="well quick-add-list">
						<h3>Quick Help</h3>
						
						<h4>Common</h4>
						<div class="quick-add" data-temp="{loopstart}{loopend}">
							Loop
						</div>
						<div class="quick-add quick-add-link">
							Link to Menu Item
						</div>
						<div class="quick-add" data-temp="{type-description}">
							Category Description
						</div>
						<div class="quick-add" data-temp="{if-property}{endif}">
							IF Property is Set
						</div>
						<div class="quick-add" data-temp="{ifimg}{endifimg}">
							IF Item has Images
						</div>
						
						<h4>Item Details</h4>
						<div class="quick-add" data-temp="{item-name}">
							Name
						</div>
						<div class="quick-add" data-temp="{item-item_id}">
							Item ID
						</div>
						<div class="quick-add" data-temp="{item-isFeatured}">
							Is Featured (1/0)
						</div>
						
						<h4>Images</h4>
						<div class="quick-add" data-temp="{img-1}">
							First Image
						</div>
						<div class="quick-add" data-temp="{img-1-path}">
							First Image Path
						</div>
						<div class="quick-add" data-temp="{img-gallery}">
							Slider Gallery
						</div>
						<div class="quick-add" data-temp="{img-grid-3}">
							Grid Gallery
						</div>
						<div class="quick-add" data-temp="{img-gridlightbox-3}">
							Grid Gallery w/ Lightbox
						</div>
						
						<?php if(count($lists['properties'])) { ?>
							<h4>Properties</h4>
							<?php foreach($lists['properties'] as $p) { 
								if($p->name == "description") {
									$hasDescription = true;
								}
								if($p->name == "shortdescription") {
									$hasShortDescription = true;
								}
								?>
								<div class="quick-add-wrapper clearfix">
									<div class="quick-add-title">
										<?php echo $p->caption;?>
									</div>
									<div class="row-fluid quick-add-btns clearfix">
										<div class="quick-add span4" data-temp="{prop-<?php echo $p->name;?>-value}">
											Value
										</div>
										<div class="quick-add span4" data-temp="{prop-<?php echo $p->name;?>-caption}">
											Caption
										</div>
										<div class="quick-add span4" data-temp="{prop-<?php echo $p->name;?>}">
											Both
										</div>
									</div>
								</div>
								
							<?php } ?>
						<?php } ?>
						
						<?php if(count($lists['children'])) { ?>
							<h4>Children</h4>
							<?php foreach($lists['children'] as $c) { ?>
								<div class="quick-add" data-temp="{children-<?php echo $c->item_category_id;?>}">
									Children - <?php echo $c->name;?>
								</div>
							<?php } ?>
						<?php } ?>
						
						<h4>Quick Templates</h4>
						<div class="quick-add" data-temp="qt-results-list">
							Basic Results - List
						</div>
						<div class="quick-add" data-temp="qt-results-grid">
							Basic Results - Grid
						</div>
						<div class="quick-add" data-temp="qt-detail">
							Basic Detail
						</div>
						<div><p></p><p></p></div>

						<div class="quick-add" data-temp="qt-callouts-results">
							Call-Outs - List
						</div>
						<div class="quick-add" data-temp="qt-events-results">
							Events - List
						</div>
						<div class="quick-add" data-temp="qt-faq-results">
							FAQs - List
						</div>
						<div class="quick-add" data-temp="qt-galleries-results">
							Galleries - List
						</div>
						<div class="quick-add" data-temp="qt-galleries-detail">
							Galleries - Detail
						</div>
						<div class="quick-add" data-temp="qt-menu-results">
							Menu - List
						</div>
						<div class="quick-add" data-temp="qt-news-results">
							News - List
						</div>
						<div class="quick-add" data-temp="qt-team-results">
							Our Team - List
						</div>
						<div class="quick-add" data-temp="qt-services-results">
							Services - List
						</div>
						<div class="quick-add" data-temp="qt-services-detail">
							Services - Detail
						</div>
						<div class="quick-add" data-temp="qt-testimonials-results">
							Testimonials - List
						</div>
						
						<p></p>
					</div>
				</div>
			</div>
			
			<input type="hidden" name="view" value="templates" />
			<input type="hidden" name="option" value="com_fl_items" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="categoryId" value="<?php echo $row->item_category_id;?>" />
			<input type="hidden" name="filename" value="<?php echo $filename;?>" />
			<input type="hidden" name="type" value="<?php echo $type;?>" />
			<?php echo JHTML::_( 'form.token' ); ?>
		</form>
		<script>
			var editor = ace.edit("phpText");
		    editor.setTheme("ace/theme/chrome");
		    editor.session.setMode("ace/mode/html");
		    
		    jQuery("#adminForm").submit(function() {
		    	var txt = editor.getValue();
		    	txt = txt.replace(/<script/ig, "<\!script");
		    	jQuery("#templateText").html(txt);
		    });
		    
		    jQuery(".quick-add-link").click(function() {
		    	jQuery(".quick-add-links").show();
		    	jQuery(".quick-add-list").hide();
		    	return false;
		    });
		    
		    jQuery(".quick-add-close").click(function() {
		    	jQuery(".quick-add-links").hide();
		    	jQuery(".quick-add-list").show();
		    	return false;
		    });
		    
			jQuery(".quick-add").click(function(e) {
				var temp = jQuery(this).attr("data-temp");
				if(temp == "qt-results-list") {
					if(confirm("Are you sure?")) {
						// List template
						temp = '<div class="<?php echo $tmpCategoryName;?>-list">\n';
						temp += '	{loopstart}\n';
						<?php if($hasImages) { ?>
						temp += '		<div class="<?php echo $tmpCategoryName;?>">\n';
						temp += '			<div class="row">\n';
						temp += '				<div class="col-md-4">\n';
						temp += '					<a href="{url}">{img-1}</a>\n';
						temp += '				</div>\n';
						temp += '				<div class="col-md-8">\n';
						<?php } ?>
						temp += '					<h4><a href="{url}">{item-name}</a></h4>\n';
						<?php if($hasShortDescription) { ?>
						temp += '					<div class="description">{prop-shortdescription-value}</div>\n';
						<?php } else if($hasDescription) { ?>
						temp += '					<div class="description">{prop-description-value}</div>\n';
						<?php } ?>
						<?php if($hasImages) { ?>
						temp += '				</div>\n';
						temp += '			</div>\n';
						temp += '		</div>\n';
						<?php } ?>
						temp += '	{loopend}\n';
						temp += '</div>';
						
						editor.setValue(temp);
						editor.focus();
					}
				} else if(temp == "qt-results-grid") {
					if(confirm("Are you sure?")) {
						// Grid template
						temp = '<div class="<?php echo $tmpCategoryName;?>-grid row fluid-grid">\n';
						temp += '	{loopstart}\n';
						temp += '		<div class="col-sm-4 fluid-grid-cell">\n';
						temp += '			<div class="<?php echo $tmpCategoryName;?>">\n';
						<?php if($hasImages) { ?>
						temp += '				<div class="<?php echo $tmpCategoryName;?>-image">\n';
						temp += '					<a href="{url}">{img-1}</a>\n';
						temp += '				</div>\n';
						<?php } ?>
						temp += '				<h4><a href="{url}">{item-name}</a></h4>\n';
						<?php if($hasShortDescription) { ?>
						temp += '				<div class="description">{prop-shortdescription-value}</div>\n';
						<?php } else if($hasDescription) { ?>
						temp += '				<div class="description">{prop-description-value}</div>\n';
						<?php } ?>
						temp += '			</div>\n';
						temp += '		</div>\n';
						temp += '	{loopend}\n';
						temp += '</div>';
						
						editor.setValue(temp);
						editor.focus();
					}
				} else if(temp == "qt-detail") {
					if(confirm("Are you sure?")) {
						// Detail template
						temp = '<div class="<?php echo $tmpCategoryName;?>">\n';
						<?php if($hasImages) { ?>
						temp += '	<div class="row">\n';
						temp += '		<div class="col-md-4">\n';
						temp += '			{img-gallery}\n';
						temp += '		</div>\n';
						temp += '		<div class="col-md-8">\n';
						<?php } ?>
						temp += '			<div class="<?php echo $tmpCategoryName;?>-details">\n';
						temp += '				<h2>{item-name}</h2>\n';
						<?php if($hasDescription) { ?>
						temp += '				<div class="description">{prop-description-value}</div>\n';
						<?php } ?>
						<?php foreach($lists['properties'] as $p) {
							if($p->name == "description" || $p->name == "shortdescription") {
								continue;
							}
							if($p->type == "heading" || $p->type == "image") {
								continue;
							}
							echo "temp += '				<div class=\"$p->name\">{prop-$p->name-value}</div>\\n';";
						}
						?>
						temp += '			</div>\n';
						<?php if($hasImages) { ?>
						temp += '		</div>\n';
						temp += '	</div>\n';
						<?php } ?>
						temp += '</div>';
						
						editor.setValue(temp);
						editor.focus();
					}
				} else if(temp == "qt-faq-results") {
					if(confirm("Are you sure?")) {
						temp = '<div class="faqs-list">\n';
						temp += '	{loopstart}\n';
						temp += '	    <div class="faq">\n';
						temp += '	        <h4 class="faq-question">{item-name}</h4>\n';
						temp += '	        <div class="faq-answer">\n';
						temp += '	            {prop-answer-value}\n';
						temp += '	        </div>\n';
						temp += '	    </div>\n';
						temp += '		<hr>\n';
						temp += '	{loopend}\n';
						temp += '</div>\n';
						temp += '<script type="application/ld+json">\n';
						temp += '{\n';
						temp += '    "@context": "https://schema.org",\n';
						temp += '    "@type": "FAQPage",\n';
						temp += '    "mainEntity": [\n';
						temp += '        {loopstart}\n';
						temp += '            {\n';
						temp += '                "@type": "Question",\n';
						temp += '                "name": "{item-name}",\n';
						temp += '                "acceptedAnswer": {\n';
						temp += '                    "@type": "Answer",\n';
						temp += '                    "text": "{prop-answer-cleanjs}"\n';
						temp += '                }\n';
						temp += '            }{ifnotlast},{endifnotlast}\n';
						temp += '        {loopend}\n';
						temp += '    ]\n';
						temp += '}\n';
						temp += '<\/script>\n';

						editor.setValue(temp);
						editor.focus();
					}
				} else if(temp == "qt-testimonials-results") {
					if(confirm("Are you sure?")) {
						temp = '<div class="testimonials-list">\n';
						temp += '	{loopstart}\n';
						temp += '		<div class="testimonial">\n';
						temp += '		    "{prop-testimonial-value}"\n';
						temp += '		</div>\n';
						temp += '		<div class="author mt-3">\n';
						temp += '		    {item-name}\n';
						temp += '		</div>\n';
						temp += '		<hr>\n';
						temp += '	{loopend}\n';
						temp += '</div>\n';

						editor.setValue(temp);
						editor.focus();
					}
				} else if(temp == "qt-callouts-results") {
					if(confirm("Are you sure?")) {
						temp = '<div class="row fluid-grid">\n';
						temp += '    {loopstart}\n';
						temp += '        <div class="col-sm-4">\n';
						temp += '            <div class="callout-inner">\n';
						temp += '                <a href="{prop-link-value}">\n';
						temp += '                    {img-1}\n';
						temp += '                    <div class="callout-title"><h4>{item-name}</h4></div>\n';
						temp += '                </a>\n';
						temp += '            </div>\n';
						temp += '        </div>\n';
						temp += '    {loopend}\n';
						temp += '</div>\n';

						editor.setValue(temp);
						editor.focus();
					}
				} else if(temp == "qt-events-results") {
					if(confirm("Are you sure?")) {
						temp = '<div class="events-list">\n';
						temp += '	{loopstart}\n';
						temp += '		<div class="event">\n';
						<?php if($hasImages) { ?>
						temp += '			<div class="row">\n';
						temp += '				<div class="col-md-3">\n';
						temp += '					{img-1}\n';
						temp += '				</div>\n';
						temp += '				<div class="col-md-9">\n';
						<?php } ?>
						temp += '					<div class="events-list-info">\n';
						temp += '						<h4>{item-name}</h4>\n';
						temp += '						<div class="date-time">\n';
						temp += '						    {prop-date-value} @ {prop-time-value}\n';
						temp += '						</div>\n';
						temp += '						<div class="location">{prop-location-value}</div>\n';
						temp += '						<div class="description">{prop-description-value}</div>\n';
						temp += '					</div>\n';
						<?php if($hasImages) { ?>
						temp += '				</div>\n';
						temp += '			</div>\n';
						<?php } ?>
						temp += '		</div>\n';
						temp += '	{loopend}\n';
						temp += '</div>\n';

						editor.setValue(temp);
						editor.focus();
					}
				} else if(temp == "qt-galleries-results") {
					if(confirm("Are you sure?")) {
						temp = '<div class="galleries-list row fluid-grid">\n';
						temp += '	{loopstart}\n';
						temp += '		<div class="col-md-6 col-lg-4 fluid-grid-cell">\n';
						temp += '			<div class="gallery mb-4">\n';
						temp += '				<div class="gallery-image">\n';
						temp += '					<a href="{url}">{img-1}</a>\n';
						temp += '				</div>\n';
						temp += '				<h4><a href="{url}">{item-name}</a></h4>\n';
						temp += '			</div>\n';
						temp += '		</div>\n';
						temp += '	{loopend}\n';
						temp += '</div>\n';

						editor.setValue(temp);
						editor.focus();
					}
				} else if(temp == "qt-galleries-detail") {
					if(confirm("Are you sure?")) {
						temp = '<div class="gallery-detail">\n';
						temp += '	<h2>{item-name}</h2>\n';
						temp += '	<div class="gallery-grid">\n';
						temp += '		{img-gridlightbox-3}\n';
						temp += '	</div>\n';
						temp += '</div>\n';

						editor.setValue(temp);
						editor.focus();
					}
				} else if(temp == "qt-menu-results") {
					if(confirm("Are you sure?")) {
						temp = '<div class="menu-list">\n';
						temp += '    <div class="row fluid-grid">\n';
						temp += '        {loopstart}\n';
						temp += '            <div class="menu-item col-md-6 fluid-grid-cell">\n';
						temp += '                <h4 class="menu-item-name">{item-name}</h4>\n';
						temp += '                <div class="menu-item-description">\n';
						temp += '                    {prop-description-value}\n';
						temp += '                    <span class="menu-item-price">{prop-price-value}</span>\n';
						temp += '                </div>\n';
						temp += '            </div>\n';
						temp += '        {loopend}\n';
						temp += '    </div>\n';
						temp += '</div>\n';

						editor.setValue(temp);
						editor.focus();
					}
				} else if(temp == "qt-news-results") {
					if(confirm("Are you sure?")) {
						temp = '<div class="news-list">\n';
						temp += '	{loopstart}\n';
						temp += '		<div class="article">\n';
						temp += '			<div class="row">\n';
						temp += '			    {ifimg}\n';
						temp += '					<div class="col-md-4">\n';
						temp += '						{img-1}\n';
						temp += '					</div>\n';
						temp += '				{endifimg}\n';
						temp += '				<div class="col-md-{ifimg}8 {endifimg}12">\n';
						temp += '					<h4>{item-name}</h4>\n';
						temp += '					<div class="article-date">\n';
						temp += '					    {prop-date-value}\n';
						temp += '					</div>\n';
						temp += '					<div class="article-body">\n';
						temp += '					    {prop-article-value}\n';
						temp += '					</div>\n';
						temp += '				</div>\n';
						temp += '			</div>\n';
						temp += '		</div>\n';
						temp += '		<hr>\n';
						temp += '	{loopend}\n';
						temp += '</div>\n';

						editor.setValue(temp);
						editor.focus();
					}
				} else if(temp == "qt-services-results") {
					if(confirm("Are you sure?")) {
						temp = '<div class="row fluid-grid">\n';
						temp += '    {loopstart}\n';
						temp += '        <div class="service col-sm-6 col-md-3 fluid-grid-cell">\n';
						temp += '            <div class="service-inner">\n';
						temp += '                <div class="service-image">\n';
						temp += '                    <a href="{url}">{img-1}</a>\n';
						temp += '                </div>\n';
						temp += '                <div class="service-details">\n';
						temp += '	                <h4><a href="{url}">{item-name}</a></h4>\n';
						temp += '                </div>\n';
						temp += '            </div>\n';
						temp += '        </div>\n';
						temp += '    {loopend}\n';
						temp += '</div>\n';

						editor.setValue(temp);
						editor.focus();
					}
				} else if(temp == "qt-services-detail") {
					if(confirm("Are you sure?")) {
						temp = '<div class="service-detail">\n';
					    temp += '	<h2>{item-name}</h2>\n';
					    temp += '	<div class="service-description">\n';
					    temp += '	    {prop-description-value}\n';
					    temp += '	</div>\n';
					    temp += '	<div class="service-gallery">\n';
					    temp += '	    {img-gridlightbox-3}\n';
					    temp += '	</div>\n';
						temp += '</div>\n';

						editor.setValue(temp);
						editor.focus();
					}
				} else if(temp == "qt-team-results") {
					if(confirm("Are you sure?")) {
						temp = '<div class="meet-the-team">\n';
						temp += '    {loopstart}\n';
						temp += '        <div class="col-md-6">\n';
						temp += '            <div class="team-member">\n';
						temp += '                <h4 class="member-name">{item-name}</h4>\n';
						temp += '                <div class="member-title">{prop-title-value}</div>\n';
						temp += '                <div class="member-email">{prop-email-value}</div>\n';
						temp += '                <div class="member-bio">{prop-bio-value}</div>\n';
						temp += '            </div>\n';
						temp += '        </div>\n';
						temp += '    {loopend}\n';
						temp += '</div>\n';

						editor.setValue(temp);
						editor.focus();
					}
				} else {
					editor.insert(temp);
					editor.focus();
				}
				
				if(jQuery(this).is(".quick-add-this-link")) {
					jQuery(".quick-add-links").hide();
		    		jQuery(".quick-add-list").show();
				}
			});
		</script>
		<?php
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
 
		FLItemsViewTemplate::setOneCategoryToolbar();
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
									</td>
								</tr>
								<tr>
									<td width="30%" class="key">
										<label for="isDescriptionEnabled">
											<?php echo JText::_( 'Enable Description?' ); ?>:
										</label>
									</td>
									<td width="70%">
										<?= FLItemsViewTemplate::getToggle("isDescriptionEnabled", $row->isDescriptionEnabled) ?>
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
										<?= FLItemsViewTemplate::getToggle("showCategory", $row->showCategory) ?>
									</td>
								</tr>
								<tr>
									<td class="key">
										<?php echo JText::_( 'Users can update published state?' ); ?>:
									</td>
									<td>
										<?= FLItemsViewTemplate::getToggle("usersUpdatePublish", $row->usersUpdatePublish) ?>
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
										<?= FLItemsViewTemplate::getToggle("hasImages", $row->hasImages) ?>
									</td>
								</tr>
								<tr>
									<td width="30%" class="key">
										<label for="isSingleImage">
											<?php echo JText::_( 'Single Image Only?' ); ?>:
										</label>
									</td>
									<td width="70%">
										<?= FLItemsViewTemplate::getToggle("isSingleImage", $row->isSingleImage) ?>
									</td>
								</tr>
								<tr>
									<td width="30%" class="key">
										<label for="hasImages">
											<?php echo JText::_( 'Enable Image Captions?' ); ?>:
										</label>
									</td>
									<td width="70%">
										<?= FLItemsViewTemplate::getToggle("hasImageCaptions", $row->hasImageCaptions) ?>
									</td>
								</tr>
								<tr>
									<td width="30%" class="key">
										<label for="description" title="">
											<?php echo JText::_( 'Image Dimensions (Width x Height)' ); ?>:
										</label>
									</td>
									<td width="70%">
										<input class="inputbox" type="text" name="imageWidth" id="imageWidth" size="25" style="width: 50px;" value="<?php echo $row->imageWidth;?>" /> x 
										<input class="inputbox" type="text" name="imageHeight" id="imageHeight" size="25" style="width: 50px;" value="<?php echo $row->imageHeight;?>" /> px
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
										<?= FLItemsViewTemplate::getToggle("isForceMenuItem", $row->isForceMenuItem) ?>
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
                                    	<?= FLItemsViewTemplate::getToggle("isNewFirst", $row->isNewFirst) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%" class="key">
                                        <label for="isFeaturedEnabled" title="">
                                            <?php echo JText::_( 'Enable "Featured" option?' ); ?>:
                                        </label>
                                    </td>
                                    <td width="70%">
                                    	<?= FLItemsViewTemplate::getToggle("isFeaturedEnabled", $row->isFeaturedEnabled) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%" class="key">
                                        <label for="usersEditOnly" title="If enabled, users can not create/delete/publish/unpublish.">
                                            <?php echo JText::_( 'Users can ONLY EDIT items?' ); ?>:
                                        </label>
                                    </td>
                                    <td width="70%">
                                    	<?= FLItemsViewTemplate::getToggle("usersEditOnly", $row->usersEditOnly) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%" class="key">
                                        <label for="isLinkToUser" title="This allows a front-end users to log in and make changes.">
                                            <?php echo JText::_( 'Link to front-end user?' ); ?>:
                                        </label>
                                    </td>
                                    <td width="70%">
                                        <?= FLItemsViewTemplate::getToggle("isLinkToUser", $row->isLinkToUser) ?>
                                    </td>
                                </tr>
								<? /* SUB-ITEMS NOT VERY COMMON. COMMENT TO AVOID CONFUSION
								<tr>
									<td width="30%" class="key">
										<label for="isSubItem">
											<?php echo JText::_( 'Sub-Item?' ); ?>:
										</label>
									</td>
									<td width="80%">
										<?php echo $lists['isSubItem']; ?>
									</td>
								</tr>
								<tr>
									<td width="20%" class="key">
										<label for="subItemParentId">
											<?php echo JText::_( 'Sub-Item parent' ); ?>:
										</label>
									</td>
									<td width="70%">
										<?php echo $lists['subItemParentId']; ?>
									</td>
								</tr>
								 */ ?>
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
