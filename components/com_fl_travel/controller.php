<?php

jimport('joomla.application.component.controller');


class FLTravelController extends JControllerLegacy
{
	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	function display()
	{
		global $config;
		$document =& JFactory::getDocument();
		
		$viewName = JRequest::getVar('view');
		$viewType = $document->getType();
		
		$view = &$this->getView($viewName, $viewType);
		
		$model = &$this->getModel( 'fl_travel' );
		$view->setModel( $model, true );
		// $modelImage = &$this->getModel( 'fl_items_image' );
		// $view->setModel( $modelImage, true );
		
		$thisSEO = $view->getSEO();
		if(is_array($thisSEO) ) {
			$document->setTitle($thisSEO['title']);
			$document->setMetaData( 'keywords', $thisSEO['meta_keywords'] );
			$document->setMetaData( 'description', $thisSEO['meta_description'] );
		}
		$o = new stdClass();
		$o->text = $view->display();
	
		JPluginHelper::importPlugin('content');
		$dispatcher = & JDispatcher::getInstance();
			
		$results = $dispatcher->trigger('onPrepareContent', array (&$o, array(), 0));
		echo $o->text;
	}

}
?>
