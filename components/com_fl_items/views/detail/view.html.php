<?php
jimport( 'joomla.application.component.view');

class FlItemsViewDetail extends JViewLegacy
{
	function display($tpl = null)
	{
		$model =& $this->getModel('fl_items');
		$document = &JFactory::getDocument();
		
		$input = JFactory::getApplication()->input;
        $menuitemid = $input->getInt( 'Itemid' );  // this returns the menu id number so you can reference parameters
        $menu = JFactory::getApplication()->getMenu();
    	$menuparams = $menu->getParams( $menuitemid );
		$itemId = $menuparams->get('item_id', 0);
		
		$itemAlias = JRequest::getString('alias', '');
		
		$this->getOneItem = $model->getOne($itemAlias, $itemId);
		$this->currentType = strtolower(JFilterOutput::stringURLSafe($this->getOneItem['item']['category']));
		if(empty($this->currentType)) {
			$this->currentType = "404";
		}
		
		$this->links = array();
		foreach($this->getOneItem['links'] as $link) {
			$this->links[$link] = $model->getOne("", $link);
		}
		if($this->getOneItem['item']['parent_item_id']) {
			$this->links[$this->getOneItem['item']['parent_item_id']] = $model->getOne("", $this->getOneItem['item']['parent_item_id']);
		}
		
		$modelGalleryImage =& $this->getModel('fl_items_image');
		if($this->getOneItem['item']['item_id']) {
			$this->getAllImages = $modelGalleryImage->getOne($this->getOneItem['item']['item_id']);
		}
		return $this->loadTemplate($tpl);
	}
	
	function getSEO() {
		$config = JFactory::getConfig();
		$siteName = $config->get( 'sitename' );
		
		$model =& $this->getModel('fl_items');
		$document = &JFactory::getDocument();
		
		$input = JFactory::getApplication()->input;
        $menuitemid = $input->getInt( 'Itemid' );  
        $menu = JFactory::getApplication()->getMenu();
    	$menuparams = $menu->getParams( $menuitemid );
		$itemId = $menuparams->get('item_id', 0);

		$itemAlias = JRequest::getString('alias', '');
		
		$model =& $this->getModel('fl_items');
		$this->getOneItem = $model->getOne($itemAlias, $itemId);
		
		$this->seo = array();
		$this->seo['title'] = $this->getOneItem['item']['name'] . ' - ' . $siteName;

		$this->seo['meta_keywords'] = $config->get('MetaKeys');
		$this->seo['meta_description'] = $config->get('MetaDesc');
		
		return $this->seo;
	}

}
?>
