<?php
/**
 * HTML View for Rentals Barefoot Component
 * 
 */

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Rentals Barefoot Component
 *
 */
class FLICViewGrid extends JViewLegacy
{
	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$menuitem = $app->getMenu()->getActive();
		$params = $menuitem->params;
		
		$this->columns = $params->get('grid-cols', 3);
		$this->padding = $params->get('padding', 10);
		
        $menu = JFactory::getApplication()->getMenu();
		$category = 0;
		$model =& $this->getModel('flic');
		$document = &JFactory::getDocument();
		
		$galleryAlias = JRequest::getVar('gallery');
		$galleryAlias = str_replace(":", "-", $galleryAlias);
		
		if(empty($galleryAlias)) {
			$menuAlias = $params->get('menu_gallery');
			$menuAlias = str_replace(":", "-", $menuAlias);
			$galleryAlias = $menuAlias;
		}
		
		$this->getOneGallery = $model->getOne($galleryAlias);
		$modelImage =& $this->getModel('flic_image');
		$this->getAllImages =  $modelImage->getAll($this->getOneGallery['flic_id']);
		$this->galleryFolderPathOriginal = '/images/flic/galleries/' . $this->getOneGallery['flic_id'] . '/original/';
		$this->galleryFolderPath = DS . 'images/flic/galleries/'. $this->getOneGallery['flic_id'] . '/';
		return $this->loadTemplate($tpl);
	}
	
	function getSEO() {
		$menu = JFactory::getApplication()->getMenu();
		$category = 0;
		$model =& $this->getModel('flic');
		$document = &JFactory::getDocument();
		
		$galleryAlias = JRequest::getVar('gallery');
		$galleryAlias = str_replace(":", "-", $galleryAlias);
		
		if(empty($galleryAlias)) {
			$app = JFactory::getApplication();
			$menuitem = $app->getMenu()->getActive();
			$params = $menuitem->params;
			$menuAlias = $params->get('menu_gallery');
			$menuAlias = str_replace(":", "-", $menuAlias);
			$galleryAlias = $menuAlias;
		}
		
		$this->getOneGallery = $model->getOne($galleryAlias);
		
		$this->seo = array();
		if(!empty($this->getOneGallery['metaTitle'])) {
			$this->seo['title'] = $this->getOneGallery['metaTitle'];
		}
		if(!empty($this->getOneGallery['metaKeywords'])) {
			$this->seo['meta_keywords'] = $this->getOneGallery['metaKeywords'];
		}
		if(!empty($this->getOneGallery['metaDescription'])) {
			$this->seo['meta_description'] = $this->getOneGallery['metaDescription'];
		}

		return $this->seo;
	}

}
?>
