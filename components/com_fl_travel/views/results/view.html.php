<?php

jimport( 'joomla.application.component.view');

class FLTravelViewResults extends JViewLegacy
{
	function display($tpl = null)
	{
		jimport('joomla.html.pagination');
		
		$jinput = JFactory::getApplication()->input;
		
		$start = $jinput->getInt("start", 0);
		$recordsPerPage = 24;
		$filter = "";
		$include = "images";
		$sort = $jinput->getString("sort", "depart_day");
		$sortOrder = $jinput->getString("sortOrder", "");
		
		$filters = array();
		$allowedFilters = array(
			"depart_day" => "date",
			"searchable_destination_id" => "id",
			"supplier_id" => "id"
		);
		
		foreach($allowedFilters as $af => $type) {
			$thisVal = $jinput->getString($af, "");
			if($thisVal) {
				if($type == "date") {
					// Correct date format
					$split = explode("/", $thisVal);
					$thisVal = "'$split[2]-$split[0]-$split[1]'";
				}
				$filters[] = "$af=$thisVal";
			}
		}
		
		if(count($filters)) {
			$filter = implode(";", $filters);
		}
		
		$model =& $this->getModel('fl_travel');
		$getAll = $model->getAll($start+1, $recordsPerPage, $filter, $include, $sort, $sortOrder);
		
		$this->allShips = $model->getAllShips();
		$this->allDestinations = $model->getAllDestinations();
		
		$this->getAllItem = $getAll['data'];
		
		$pageNav = new JPagination( $getAll['totalCount'], $start, $recordsPerPage );

		$thisContent = $this->loadTemplate($tpl) . $pageNav->getPagesLinks();

		return $thisContent;
		
		
		
		$input = JFactory::getApplication()->input;
        $menuitemid = $input->getInt( 'Itemid' );  // this returns the menu id number so you can reference parameters
        $menu = JFactory::getApplication()->getMenu();
		
		$category = 0;
		$recordsPerPage = 30;
		$orderBy = "";
		$featuredOnly = 0;
		
		$this->topTitle = "";
		
		$menuSearches = array();
        if ($menuitemid) {
        	$menuparams = $menu->getParams( $menuitemid );
			$category = $menuparams->get('item_category_id', 0);
			$this->topTitle = $menuparams->get('top_title', "");
			
			$menuOrderBy = $menuparams->get('order_by', "");
			if($menuOrderBy) {
				$orderBy = $menuOrderBy;
			}
			
			$menuLimit = $menuparams->get('limit', "");
			if($menuLimit) {
				$recordsPerPage = $menuLimit;
			}
			
			$featuredOnly = $menuparams->get("featured_only", 0);
			
			$menuSearch = $menuparams->get('search_params', "");
			$msSplit = explode("&", $menuSearch);
			foreach($msSplit as $ms) {
				$ms = str_replace("=", "_", $ms);
				$ms = str_replace("*MONTH*", strtotime(date("m/01/Y")), $ms);
				$ms = str_replace("*DAY*", strtotime(date("m/d/Y")), $ms);
				// $ms = str_replace("-", "_", $ms);
				$menuSearches[] = $ms;
			}
        }
		
		if(empty($category)) {
			$category = $input->getInt('item_category_id', 0);
		}
		
		$searchTerms = $input->getString("search");
		if(!empty($searchTerms)) {
			$searchTerms = array($searchTerms);
		}
		foreach($_GET as $k => $v) {
			if($k == "search" || $k == "start") {
				continue;
			}
			if($k && $v) {
				$searchTerms[] = $k . "_" . $v;
			}
		}
		foreach($menuSearches as $ms) {
			if(!empty($ms)) {
				$searchTerms[] = $ms;
			}
		}
		
		$params = &JComponentHelper::getParams( 'com_fl_items' );
		
		$recordStart = JRequest::getVar('start', 0, '', 'int');
		
		$model =& $this->getModel('fl_items');
		
		if($category) {
			$this->getAllItem = $model->getAll( $recordsPerPage, $recordStart, $category, $searchTerms, 0, 0, $orderBy, $featuredOnly );
			$this->getAllItemCount = $model->getAllCount($category, $searchTerms);
			$this->getCategory = $model->getCategory($category);
		} else {
			$this->getAllItem = $model->getAll( $recordsPerPage, $recordStart, 0, $searchTerms, 0, 0, $orderBy, $featuredOnly);
			$this->getAllItemCount = $model->getAllCount(0, $searchTerms);
			$this->getCategory = null;
		}
		
		$this->links = array();
		foreach($this->getAllItem['links'] as $link) {
			$this->links[$link] = $model->getOne("", $link);
		}
		unset($this->getAllItem['links']);
		
		$this->options = array();
		if(isset($this->getAllItem['options'])) {
			$this->options = $this->getAllItem['options'];
		}
		unset($this->getAllItem['options']);
		
		$this->currentType = "";
		if($this->getAllItemCount > 0) {
			$this->currentType = array_values($this->getAllItem);
			$this->currentType = strtolower(JFilterOutput::stringURLSafe($this->currentType[0]['item']['category']));
		} else if($category) {
			$this->currentType = $category."-no";
		} else {
			$this->currentType = "nonefound";
		}
		
		$modelGalleryImage =& $this->getModel('fl_items_image');
		$this->getAllImages = $modelGalleryImage->getAll();
		
		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $this->getAllItemCount, $recordStart, $recordsPerPage );

		$thisContent = $this->loadTemplate($tpl) . $pageNav->getPagesLinks();

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
