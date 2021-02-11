<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_title
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$links = array();

if (JComponentHelper::getComponent('com_fl_items', true)->enabled)
{
	$links[] = array("FL Items", "/administrator/index.php?option=com_fl_items", "items");
}
if (JComponentHelper::getComponent('com_eshop', true)->enabled)
{
	$links[] = array("EShop", "/administrator/index.php?option=com_eshop", "cart");
}
if (JComponentHelper::getComponent('com_rseventspro', true)->enabled)
{
	$links[] = array("Events", "/administrator/index.php?option=com_rseventspro&view=events", "events");
}
if (JComponentHelper::getComponent('com_eventbooking&view', true)->enabled)
{
	$links[] = array("Events", "/administrator/index.php?option=com_eventbooking&view=events", "events");
}
$links[] = array("Banners", "/administrator/index.php?option=com_flic", "banners");
$links[] = array("Articles", "/administrator/index.php?option=com_content", "articles");
$links[] = array("Modules", "/administrator/index.php?option=com_modules", "modules");

if (JComponentHelper::getComponent('com_rsform', true)->enabled)
{
	$links[] = array("Contact Forms", "/administrator/index.php?option=com_rsform&view=forms", "forms");
}
$links[] = array("SEO", "/administrator/index.php?option=com_rsseo&view=pages", "seo");
$links[] = array("Menus", "/administrator/index.php?option=com_menus&view=items&menutype=mainmenu", "menu");
$links[] = array("Users", "/administrator/index.php?option=com_users", "users");

?>

<style>
	.quick-wrapper {
	    background: #f9f9f9;
	    padding: 15px 15px 0;
	    border: 1px solid #ccc;
	    border-radius: 5px;
	}
	.quick-wrapper .quick-button-image {
		margin-bottom: 10px;
	}
	.quick-wrapper a {
		width: calc(100% - 30px);
		padding: 25px 15px;
		font-weight: bold;
	    font-size: 15px;
	}
</style>

<div class='quick-wrapper'>
	<div class="row-fluid" style='padding-bottom: 15px;'>
		<?php
		$c = 0;
		foreach($links as $l) {
			if($c && $c % 4 == 0) {
				echo "
					</div>
					<div class='row-fluid' style='padding-bottom: 15px;'>
				";
			}
			echo "
			<div class='span3'>
				<a href='$l[1]' class='btn'>
					<div class='quick-button-image'>
						<img src='/administrator/modules/mod_fl_quick/assets/$l[2].png'>
					</div>
					$l[0]
				</a>
			</div>
			";
			
			$c++;
		}
		?>
	</div>
</div>