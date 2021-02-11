<?php
defined('_JEXEC') or die('Restricted access');

JHtml::_('jquery.framework');

$document = &JFactory::getDocument();
$document->addStyleSheet('/components/com_fl_items/assets/slick.css'); 
$document->addScript($this->baseurl . '/components/com_flic/assets/slick.min.js', 'text/javascript');

$templateBasePath = JPATH_BASE."/components/com_fl_items/templates/";
$templatePath = JPATH_BASE."/components/com_fl_items/templates/" . JFilterOutput::stringURLSafe($this->currentType) . "-";
$templateName = "results";

$model =& $this->getModel('fl_items');

if(count($this->getAllItem) == 0 ) {
	if(!empty($templateName) && file_exists($templatePath.$templateName.".php")) {
		$noResults = str_replace("<!script", "<script", file_get_contents($templatePath.$templateName.".php") );
		echo $noResults;
	} else if(file_exists($templateBasePath."no-results.php")) {
		$noResults = str_replace("<!script", "<script", file_get_contents($templateBasePath."no-results.php") );
		echo $noResults;
	} else {
		echo '<p>No items found.</p>';
	}
} else {
	if(!empty($templateName) && file_exists($templatePath.$templateName.".php")) {
		require_once(JPATH_BASE."/administrator/components/com_fl_items/helpers/templateBuilder.php");
		$templateBuilder = new TemplateBuilder();
		$template = str_replace("<!script", "<script", file_get_contents($templatePath.$templateName.".php") );
		
		$lookFrom = 0;
		if(strpos($template, "{children-", $lookFrom) !== false) {
			while(strpos($template, "{children-", $lookFrom) !== false) {
				$foundAt = strpos($template, "{children-", $lookFrom);
				$closingBracket = strpos($template, "}", $foundAt);
				$categoryId = substr($template, $foundAt + 10, $closingBracket - $foundAt - 10);

				$children = array();	
				$childrenImages = array();
				$modelGalleryImage =& $this->getModel('fl_items_image');

				foreach($this->getAllItem as $i) {
					$getChildren = $model->getAllByParentId($i['item']['item_id'], $categoryId);
					if(count($getChildren)) {
						foreach($getChildren as $iid => $gc) {
							if($gc && is_numeric($iid)) {
								$children[$i['item']['item_id']][$iid] = $gc;
								$childrenImages[$iid] = $modelGalleryImage->getOne($iid);
							}
						}
					}
				}
				$lookFrom = $foundAt +1;
			}
		}
		
		$templateBuilder->addOptions($this->options);
		$templateBuilder->addItems($this->getAllItem);
		$templateBuilder->addImages($this->getAllImages);
		$templateBuilder->addLinks($this->links);
		$templateBuilder->addChildren($children, $childrenImages);
		$templateBuilder->setCategory($this->getCategory);
		
		if($this->topTitle) {
			echo "<div class='page-header'><h2>$this->topTitle</h2></div>";
		}
		print '<div class="item-results">';
			print $templateBuilder->buildTemplate($template);
		print '</div>';
	} else {
		print "Template not found: " . $templatePath.$templateName.".php";
	}
}

?>
