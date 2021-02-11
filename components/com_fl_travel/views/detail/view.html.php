<?php

jimport( 'joomla.application.component.view');

class FLTravelViewDetail extends JViewLegacy
{
	function display($tpl = null)
	{
		jimport('joomla.html.pagination');
		
		$jinput = JFactory::getApplication()->input;
		
		$offerId = $jinput->getInt("offer_id", 0);
		
		$model =& $this->getModel('fl_travel');
		$this->getOne = $model->getOne($offerId);
		
		$thisContent = $this->loadTemplate($tpl);

		return $thisContent;
		
	}
	
	function getSEO() {
		$config = JFactory::getConfig();
		$siteName = $config->get( 'sitename' );
		
		$this->seo = array();
		$menuTitle = &JFactory::getApplication()->getMenu()->getActive()->title;
		$this->seo['title'] = $menuTitle . ' - ' . $siteName;

		$this->seo['meta_keywords'] = $config->get('MetaKeys');
		$this->seo['meta_description'] = $config->get('MetaDesc');
		
		return $this->seo;
	}
}
?>
