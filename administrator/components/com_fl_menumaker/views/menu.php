<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class FL_MenumakerViewMenu
{
	function setToolbar()
	{
		JToolBarHelper::title( JText::_( 'FL Menu Builder' ), 'generic.png' );
        JToolBarHelper::custom( 'build', 'generic.png', 'generic.png', 'Build Menu', false, false );
		JToolBarHelper::preferences('com_fl_menumaker', '200');
	}

	function menu( $lists )
	{
		FL_MenumakerViewMenu::setToolbar();
		?>
        <style>
            .item-row{padding-bottom: 5px;}
            .item-row .btn{margin-left: 5px;}
        </style>
        <p><strong>TIP:</strong> If you set up FL Item Types first you can set up those FL Item pages at the same time! :D</p>
        <p><strong>TIP #2:</strong> When you're done, disabled the component under "<strong>Extensions > Manage > Manage</strong>" to prevent... <strong>(╯°□°)╯︵ ┻━┻</strong></p>
        <p style="color: white;">Hello Friend. You sneaky.</p>
		<form action="index.php?option=com_fl_menumaker" class="form-inline" method="post" name="adminForm" id="adminForm">

            <div class="item-tree">
                <div class="root item item-0 item-row">
                    <div class="item-name"></div> <a class="btn btn-primary new-item" data-level="0" data-id="0" href="javascript:;">Add Root Item</a>
                </div>
                <div class="item-children" data-parent="0"></div>
            </div>

            <input type="hidden" name="c" value="menu" />
            <input type="hidden" name="option" value="com_fl_menumaker" />
            <input type="hidden" name="task" value="" />
            <?php echo JHTML::_( 'form.token' ); ?>
		</form>

        <script>
            var currentId = 0;
            jQuery(document).ready(function() {
                jQuery(".item-tree").on("click", ".new-item", function() {
                    currentId++;
                    var level = jQuery(this).attr("data-level");
                    var thisId = jQuery(this).attr("data-id");
                    var nextLevel = parseInt(level) + 1;
                    var spacer = "";
                    var flItemTypeOptions = "<?php if($lists['itemTypes']) {
                    	foreach($lists['itemTypes'] as $it) {
                    		echo "<option value='2=$it->item_category_id'>FL Item - $it->name</option>";
                    	}	
                    }?>";
                    for(var i = 0; i < level; i++ ){
                        spacer += "&nbsp;-&nbsp;&nbsp;";
                    }
                    jQuery(this).parent().next().append('<div class="item-row">' + spacer + '<input name="menu-'+currentId+'" type="text" class="form-control new-data" placeholder="Name">' +
                        '<a tabIndex="-1" class="btn btn-primary new-item" data-level="' + nextLevel + '" data-id="'+currentId+'" href="javascript:;">+</a>' +
                        '<a tabIndex="-1" class="btn btn-danger delete-item" href="javascript:;">-</a> ' +
                        '<select class="form-control page-type"><option value="0">Empty</option><option value="1" selected="selected">Article</option>'+flItemTypeOptions+'</select>' +
                        '</div><div class="item-children" data-parent="'+currentId+'"></div>');
                });

                jQuery(".item-tree").on("click", ".delete-item", function() {
                    jQuery(this).parent().next().remove();
                    jQuery(this).parent().remove();
                });

                jQuery("#adminForm").submit(function(e) {
                    jQuery(".new-data").each(function() {
                        var title = jQuery(this).val();
                        var level = jQuery(this).next().attr("data-level");
                        var parent = jQuery(this).parents(".item-children").attr("data-parent");
                        var needsArticle = jQuery(this).next().next().next().val();

                        jQuery(this).val(title + "---" + level + "---" + parent + "---" + needsArticle);
                    });
                });
            });
        </script>

        <p style="color: white;">I'm not your friend, buddy.</p>
        <p style="color: white;">I'm not your buddy, pal.</p>
		<?php
	}
}
