<?php
defined('_JEXEC') or die('Restricted access');

require_once('components/com_fl_items/models/fl_items.php');
require_once('components/com_fl_items/models/fl_items_image.php');
$model = new FLItemsModelfl_items();
$imageModel = new FLItemsModelfl_items_image();

$getOne = false;
$featuredOnly = $params->get("featured_only", 0);
$categoryId = $params->get("item_category_id", 0);
if(empty($categoryId)) {
	$itemId = $params->get("item_id", 0);
	if(!empty($itemId)) {
		$getOne = true;
	}
}
$ordering = $params->get("ordering", "");

$searchParams = $params->get("search_params", "");
$searchParams = str_replace("*MONTH*", strtotime(date("m/01/Y")), $searchParams);
$searchParams = str_replace("*DAY*", strtotime(date("m/d/Y")), $searchParams);
$msSplit = explode("&", $searchParams);
$search = array();
foreach($msSplit as $ms) {
	$ms = str_replace("=", "_", $ms);
	$ms = str_replace("*MONTH*", strtotime(date("m/01/Y")), $ms);
	$ms = str_replace("*DAY*", strtotime(date("m/d/Y")), $ms);
	// $ms = str_replace("-", "_", $ms);
	$search[] = $ms;
}

// Template name found in /components/templates/--NAME--.php
$templateName = $params->get("template", "");
$currentType = "module";

$document = &JFactory::getDocument();
$document->addStyleSheet('/components/com_fl_items/assets/slick.css'); 
$document->addScript('/components/com_fl_items/assets/slick.min.js', 'text/javascript');

$templatePath = JPATH_BASE."/components/com_fl_items/templates/" . $currentType . "-";

if(!empty($templateName) && file_exists($templatePath.$templateName.".php")) {
	require_once(JPATH_BASE."/administrator/components/com_fl_items/helpers/templateBuilder.php");
	require_once(JPATH_BASE."/administrator/components/com_fl_items/helpers/templateBuilderImages.php");
	$templateBuilder = new TemplateBuilder();
	
	$thisTemplate = file_get_contents($templatePath.$templateName.".php");
	$thisTemplate = str_replace("<!script", "<script", $thisTemplate);
	
	if($getOne) {
		$item = $model->getOne("", $itemId);
		if($item) {
			$images = $imageModel->getOne($itemId);
			
			$options = array();
			if(is_array($item['option'])) {
				$options = $item['option'];
				unset($item['option']);
			}
			$templateBuilder->addOptions($options);
			$templateBuilder->addItems(array($item));
			$templateBuilder->addImages($images);
			$templateBuilder->addLinks($links);
			// $templateBuilder->addChildren($children);
			// $templateBuilder->setCategory($this->getCategory);
		
			print $templateBuilder->buildTemplate($thisTemplate);
		}
		
	} else {
		$limit = $params->get("limit", 1000);
		// Items
		$items = $model->getAll($limit, 0, $categoryId, $search, 0, 0, $ordering, $featuredOnly);
		// Links
		if(is_array($items['links'])) {
			$links = $items['links'];
			unset($items['links']);
		}
		// Parents
		$parents = array();
		foreach($items as $i) {
			if(!in_array($i['item']['parent_item_id'], $parents)) {
				$parents[] = $i['item']['parent_item_id'];
			}
		}
		foreach($parents as $p) {
			$links[$p] = $model->getOne("", $p);
		}
		// Options
		$options = array();
		if(is_array($items['options'])) {
			$options = $items['options'];
			unset($items['options']);
		}
		// Category 
		$getCategory = $model->getCategory($categoryId);
		
		if(count($items) == 0 ) {
			$templateName = "$categoryId-no-results";
			if(file_exists($templatePath.$templateName.".php")) {
				$noResults = file_get_contents($templatePath.$templateName.".php");
				echo $noResults;
			} else if(file_exists($templatePath."no-results.php")) {
				$noResults = file_get_contents($templatePath."no-results.php");
				echo $noResults;
			} else {
				echo '<p>No items found.</p>';
			}
		} else {
			$images = $imageModel->getAll();
			
			$templateBuilder->addOptions($options);
			$templateBuilder->addItems($items);
			$templateBuilder->addImages($images);
			$templateBuilder->addLinks($links);
			// $templateBuilder->addChildren($children);
			$templateBuilder->setCategory($getCategory);
			
			print $templateBuilder->buildTemplate($thisTemplate);
		}
	}
} else {
	print "Template not found: " . $templatePath.$templateName.".php";
}

?>
