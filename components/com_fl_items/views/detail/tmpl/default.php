<?php
defined('_JEXEC') or die('Restricted access');

// Template name: found in /components/templates/--NAME--.php
$templateName = "detail";

JHtml::_('jquery.framework');

$document = &JFactory::getDocument();
$document->addStyleSheet('/components/com_fl_items/assets/slick.css'); 
$document->addScript($this->baseurl . '/components/com_flic/assets/slick.min.js', 'text/javascript');

$templatePath = JPATH_BASE."/components/com_fl_items/templates/" . $this->currentType . "-";
if($this->currentType == "404") {
	JError::raiseError(404, JText::_('COM_CONTENT_ERROR_ARTICLE_NOT_FOUND'));
}

if(!empty($templateName) && file_exists($templatePath.$templateName.".php")) {
	require_once(JPATH_BASE."/administrator/components/com_fl_items/helpers/templateBuilder.php");
	
	$children = array();
	$childrenImages = array();		
	$modelGalleryImage =& $this->getModel('fl_items_image');	
	
	$templateBuilder = new TemplateBuilder();
	$template = file_get_contents($templatePath.$templateName.".php");
	// Clean-up Script Tags
	$template = str_replace("<!script", "<script", $template);

	$model = $this->getModel('fl_items');

	if(strpos($template, "{children-", $lookFrom) !== false) {
		$lookFrom = 0;
		while(strpos($template, "{children-", $lookFrom) !== false) {
			$foundAt = strpos($template, "{children-", $lookFrom);
			$closingBracket = strpos($template, "}", $foundAt);
			$categoryId = substr($template, $foundAt + 10, $closingBracket - $foundAt - 10);

			$getChildren = $model->getAllByParentId($this->getOneItem['item']['item_id'], $categoryId);
			$cLinks = array();
			foreach($getChildren['links'] as $link) {
				$cLinks[$link] = $model->getOne("", $link);
			}
			unset($getChildren['links']);
			
			$cOptions = array();
			if(isset($getChildren['options'])) {
				$cOptions = $getChildren['options'];
			}
			unset($getChildren['options']);
			
			if(count($getChildren)) {
				foreach($getChildren as $iid => $gc) {
					if($gc && is_numeric($iid)) {
						if(count($cOptions)) {
							$gc['item']['options'] = $cOptions;
						}
						$children[$this->getOneItem['item']['item_id']][$iid] = $gc;
						$childrenImages[$iid] = $modelGalleryImage->getOne($iid);
					}
				}
			}
			
			$lookFrom = $foundAt +1;
		}
	}
	
	// Find any LinkLoops and get their data and/or templates
	$linkLoops = array();
	$linkLoopTemplates = array();
	if(strpos($template, "{linkloop-", $lookFrom) !== false) {
		$lookFrom = 0;
		while(strpos($template, "{linkloop-", $lookFrom) !== false) {
			$foundAt = strpos($template, "{linkloop-", $lookFrom);
			$closingBracket = strpos($template, "}", $foundAt);
			$loopInfo = substr($template, $foundAt + 10, $closingBracket - $foundAt - 10);
			
			$split = explode("-", $loopInfo);
			$loopId = $split[0];
			$loopTemplate = $split[1];
			if(!in_array($loopId, $linkLoops)) {
				$linkLoops[] = $loopId;
			}
			if(!in_array($loopTemplate, $linkLoopTemplates)) {
				$linkLoopTemplates[] = $loopTemplate;
			}
			
			$lookFrom = $foundAt +1;
		}
		
		$linkLoopData = array();
		$linkLoopTemplateData = array();
		
		foreach($linkLoops as $ll) {
			$linkLoopData[$ll] = $model->getAllWhere($ll, $this->getOneItem['item']['item_id']);
		}
		foreach($linkLoopTemplates as $llt) {
			$linkLoopTemplateData[$llt] = str_replace("<!script", "<script", file_get_contents(JPATH_BASE."/components/com_fl_items/templates/linkloop-$llt.php") );
		}
		
		$this->linkLoops = array("data" => $linkLoopData, "templates" => $linkLoopTemplateData);
	}

	// Find any SubLoops and get their data and/or templates
	$subLoops = array();
	$subLoopTemplates = array();
	if(strpos($template, "{subloop-", $lookFrom) !== false) {
		$lookFrom = 0;
		while(strpos($template, "{subloop-", $lookFrom) !== false) {
			$foundAt = strpos($template, "{subloop-", $lookFrom);
			$closingBracket = strpos($template, "}", $foundAt);
			$loopInfo = substr($template, $foundAt + 9, $closingBracket - $foundAt - 9);
			
			$split = explode("-", $loopInfo);
			$loopItemCat = $split[0];
			$loopTemplate = $split[1];
			if(!in_array($loopId, $subLoops)) {
				$subLoops[] = $loopItemCat;
			}
			if(!in_array($loopTemplate, $subLoopTemplates)) {
				$subLoopTemplates[] = $loopTemplate;
			}
			
			$lookFrom = $foundAt +1;
		}
		
		$model = $this->getModel('fl_items');
		
		$subLoopData = array();
		$subLoopTemplateData = array();
		
		foreach($subLoops as $sl) {
			$subLoopData[$sl] = $model->getAllByParentId($this->getOneItem['item']['item_id'], $sl);
		}
		foreach($subLoopTemplates as $llt) {
			$subLoopTemplateData[$llt] = str_replace("<!script", "<script", file_get_contents(JPATH_BASE."/components/com_fl_items/templates/subloop-$llt.php") );
		}
		
		$this->subLoops = array("data" => $subLoopData, "templates" => $subLoopTemplateData);
	}

	$templateBuilder->addOptions($this->getOneItem['option']);
	$templateBuilder->addItems(array($this->getOneItem['item']['item_id'] => $this->getOneItem));
	$templateBuilder->addImages(array($this->getOneItem['item']['item_id'] => $this->getAllImages));
	$templateBuilder->addLinks($this->links);
	$templateBuilder->addChildren($children, $childrenImages);
	print $templateBuilder->buildTemplate($template);
	
	// print buildTemplate($template, $this->getOneItem, $this->getAllImages, $this->links, $this->linkLoops, $children);
} else {
	print "Template not found: " . $templatePath.$templateName.".php";
}

?>
