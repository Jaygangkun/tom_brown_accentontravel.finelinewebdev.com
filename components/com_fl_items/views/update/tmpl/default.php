<?php
defined('_JEXEC') or die('Restricted access');

// Template name: found in /components/templates/--NAME--.php
$templateName = "edit";

JHtml::_('jquery.framework');

$document = &JFactory::getDocument();
$document->addStyleSheet('/components/com_fl_items/assets/slick.css'); 
$document->addScript($this->baseurl . '/components/com_flic/assets/slick.min.js', 'text/javascript');

$templatePath = JPATH_BASE."/components/com_fl_items/templates/" . $this->currentType . "-";

if(!empty($templateName) && file_exists($templatePath.$templateName.".php")) {
	require_once(JPATH_BASE."/administrator/components/com_fl_items/helpers/templateBuilder.php");
	$template = file_get_contents($templatePath.$templateName.".php");
	
	print buildTemplate($template, $this->getOneItem, $this->getAllImages, $this->links, $this->linkLoops, $this->subLoops);
} else {
	print "Template not found: " . $templatePath.$templateName.".php";
}

?>



