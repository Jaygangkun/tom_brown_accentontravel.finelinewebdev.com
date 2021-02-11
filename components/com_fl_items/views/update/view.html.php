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
class FlItemsViewUpdate extends JViewLegacy
{
	function display($tpl = null)
	{
		$model =& $this->getModel('fl_items');
		$document = &JFactory::getDocument();

		$user = JFactory::getUser();
		if($user->guest == 1) {
			$app = JFactory::getApplication();
			$message = "You must log in to access this page.";
			$app->redirect(JRoute::_('index.php?option=com_users', false), $message, 'error');
		}

		$this->getEditItems = $model->getEditItems($user->id);

		$urlId = JRequest::getInt('id', 0);
		$isCreate = JRequest::getInt('create', 0);
		$categoryId = JRequest::getInt('category', 0);
		$parentId = JRequest::getInt('parent', 0);

		if(count($this->getEditItems)) {
			if(count($this->getEditItems) > 1 && $urlId == 0 && !$isCreate) {
				$tpl = "select";
				return $this->loadTemplate($tpl);
			} else {
				if($isCreate) {
					if($categoryId) {
						$tempItemId = $model->createNewTempItem($categoryId, $parentId);
						$this->getOneItem = $model->getOneForEdit($tempItemId);
					} else {
						return "An error occurred.";
						exit;
					}
				} else if($urlId) {
					$this->getOneItem = $model->getOneForEdit($urlId, $user->id);
				} else {
					$this->getOneItem = $model->getOneForEdit($this->getEditItems[0]['item_id']);
				}

				if(!$this->getOneItem) {
					return "An error occurred.";
					exit;
				}

				$this->currentType = str_replace(" ", "-", strtolower($this->getOneItem['item']['category']));
				
				$this->links = array();
				foreach($this->getOneItem['links'] as $link) {
					$this->links[$link] = $model->getOne($link);
				}
				
				$modelGalleryImage =& $this->getModel('fl_items_image');
				$this->getAllImages = $modelGalleryImage->getOne($this->getOneItem['item']['item_id']);
				return $this->loadTemplate($tpl);
			}

		} else {
			return "No objects are linked to your account. If you believe this is a mistake, please contact us!";
		}

		exit;
	}
	
	function getSEO() {
		$config = JFactory::getConfig();
		$siteName = $config->get( 'sitename' );
		
		$model =& $this->getModel('fl_items');
		$this->getOneItem = $model->getOne(JRequest::getInt('item_id'));
		
		$this->seo = array();
		$this->seo['title'] = $this->getOneItem['item']['name'] . ' - ' . $siteName;
		$thisKeywords = array();
		$thisKeywords[] = $this->getOneItem['item']['name'];
		if( strlen($this->getOneItem['shortDescription']) ) {
			$thisKeywords[] = $this->getOneItem['shortDescription'];
		}
		if( strlen($this->getOneItem['location']) ) {
			$thisKeywords[] = $this->getOneItem['location'];
		}
		if( strlen($this->getOneItem['itemType']) ) {
			$thisKeywords[] = $this->getOneItem['itemType'];
		}
		if( strlen($this->getOneItem['client']) ) {
			$thisKeywords[] = $this->getOneItem['client'];
		}
		$this->seo['meta_keywords'] = implode(', ', $thisKeywords);
		$this->seo['meta_description'] = substr(strip_tags($this->getOneItem['description']), 0, 50);
		

		return $this->seo;
	}

}
?>
