<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if (!JFactory::getUser()->authorise('core.manage', 'com_fl_items'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Set the table directory
JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_fl_items/tables');

// Load the helper classes
JLoader::register('FliItem', JPATH_ADMINISTRATOR . '/components/com_fl_items/helpers/item/fliItem.php');
JLoader::register('FliField', JPATH_ADMINISTRATOR . '/components/com_fl_items/helpers/fields/fliField.php'); 

$controllerName = JRequest::getCmd( 'view', 'fl_items' );
$taskName = JRequest::getCmd( 'task', 'display' );

// Setup language
$language = JFactory::getLanguage();
$language->load('com_fl_items', JPATH_ADMINISTRATOR, 'en-US', true);

$db =& JFactory::getDBO();
$query = 'SELECT * FROM #__fl_items_category WHERE showCategory = 1 AND isSubItem = 0 ORDER BY ordering';

$db->setQuery( $query );
$types = $db->loadObjectList();

$categoryId = $_GET['item_category_id'];
if(empty($categoryId)) {
	$categoryId = $_POST['item_category_id'];
}
if(empty($categoryId)) {
	// $categoryId = $types[0]->item_category_id;
}

$user =& JFactory::getUser();
$isroot = $user->authorise('core.admin');

// Build menu
$c = 0;
$foundHeaders = array();
foreach($types as $type) {
	if(!$type->isHiddenParent || $type->item_category_id == $categoryId) {
		$c++;
		if($type->isHeader) {
			JSubMenuHelper::addEntry("<strong>$type->name</strong>");
			$foundHeaders[] = $c;
		} else {
			JSubMenuHelper::addEntry($type->name, 'index.php?option=com_fl_items&item_category_id=' . $type->item_category_id, $type->item_category_id == $categoryId);
		}
	}
}
if($isroot) {
	JSubMenuHelper::addEntry("<strong>Admin Settings</strong>");
	JSubMenuHelper::addEntry('<strong>Item Types</strong>', 'index.php?option=com_fl_items&view=categories', $controllerName == "categories"); 
	JSubMenuHelper::addEntry('<strong>Properties</strong>', 'index.php?option=com_fl_items&view=properties', $controllerName == "properties");
	JSubMenuHelper::addEntry('<strong>Templates</strong>', 'index.php?option=com_fl_items&view=templates', $controllerName == "templates");
	JSubMenuHelper::addEntry('<strong>Setup Wizard</strong> <small>Lazy Mode</small>', 'index.php?option=com_fl_items&view=lazy', $controllerName == "lazy");
}


$doc = JFactory::getDocument();
$doc->addStyleSheet('/administrator/components/com_fl_items/assets/fl_items.css');

require_once( JPATH_COMPONENT.'/controllers/'.$controllerName.'.php' );
$controllerName = 'FLItemsController'.$controllerName;


// Create the controller
$controller = new $controllerName();

// Perform the Request task
$controller->execute( JRequest::getCmd('task') );

// Redirect if set by the controller
$controller->redirect();

echo "<script>";
	if($isroot) {
		echo "
			jQuery('body').addClass('super-fl-item');
		";
	}
	foreach($foundHeaders as $headerId) {
		echo "jQuery('#submenu > li:nth-of-type($headerId)').addClass('header');";
	}
	$c++;
	echo "jQuery('#submenu > li:nth-of-type($c)').addClass('header').addClass('root-header');";
echo "</script>";
