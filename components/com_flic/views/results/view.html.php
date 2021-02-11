<?php

jimport( 'joomla.application.component.view');

class FLICViewresults extends JViewLegacy
{
	function display($tpl = null)
	{
		$input = JFactory::getApplication()->input;
        $menuitemid = $input->getInt( 'Itemid' );  // this returns the menu id number so you can reference parameters
        $menu = JFactory::getApplication()->getMenu();
		$pageTitle = $menu->getActive()->title;
		$category = 0;
        if ($menuitemid) {
        	$menuparams = $menu->getParams( $menuitemid );
			$category = str_replace(":" , "-" , $menuparams->get('category', 0));
			$menuCategory = $category;
			$recordsPerPage = $menuparams->get('recordsPerPage', 30);
        }
		
		$getCategory = JRequest::getVar('category', '');
		if($getCategory) {
			for($i = 0 ; $i < count($getCategory) ; $i++) { //})($getCategory as $cat) {
				$cat = str_replace(":" , "-" , $getCategory[$i]);
				$category = $cat;
				$this->currentCategoryList .= "/$cat";
				if($i < count($getCategory) - 1) {
					$this->parentCategoryList .= "/$cat";
				}
			}
		}
		
		$this->showBackLink = 1;
		if($menuCategory == $category || is_numeric($category)) {
			$this->showBackLink = 0;	
		}
		
		$params = &JComponentHelper::getParams( 'com_flic' );
		
		$recordsPerPage = 30;
		
		$pageNumber = JRequest::getInt('page', 1);
		$recordStart = $recordsPerPage * ($pageNumber - 1);
		
		$model =& $this->getModel('flic');
		$modelGalleryImage =& $this->getModel('flic_image');
		
		$this->getSubCategories = array();

		$this->getAllGallery =  $model->getCategory( $recordsPerPage, $recordStart, $category );
		$this->getAllGalleryCount =  $model->getCategoryCount();
		$this->getSubCategories = $model->getSubCategories($category);
		
		$galleryIdString = "";
		foreach($this->getAllGallery as $gallery) {
			$galleryIdString .= $gallery->flic_id . ",";
		}
		$this->getAllImages =  $modelGalleryImage->getAllGalleryImages(substr($galleryIdString, 0, strlen($galleryIdString)-1));

		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $this->getAllGalleryCount, $recordStart, $recordsPerPage );

		$thisContent .=  "<h2>$pageTitle</h2>". $this->loadTemplate($tpl) . $pageNav->getPagesLinks(  );

		return $thisContent;

	}
	
	function getSEO() {
		return '';
	}
	
}
?>
