<?php
/**
* @version $Id: FLuid.menuview.class.php 01-16-2013 10:25
* @author Thomas Brown
* @package exmenu
* @copyright (C) 2005-2011 Daniel Ecer (de.siteof.de)
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

// no direct access
if (!defined('EXTENDED_MENU_HOME')) {
	die('Restricted access');
}

/*** This Menu View is used for menu style "Fluid" ***/
class FluidnocollapseExtendedMenuView extends AbstractExtendedMenuView {
	/* menu link classes*/
	function getMenuClassName($menuNode, $level = 0, $activeMenuClass = TRUE) {
		if ($level > 0) {
			$menuClass = 'sublevel';
		} else {
			$menuClass = 'mainlevel';
		}
		if (($activeMenuClass) && ($menuNode->isActive())) {
			if ($menuNode->isCurrent()) {
				$menuClass	.= ' current';
			} else {
				$menuClass	.= ' active';
			}
		}
		return $menuClass;
	}
	/* expanding menu links */	
	
	
	/**
	* Utility function for writing a menu link
	* (modification of the original menu module mosGetMenuLink function)
	*/
	function mosGetMenuLink($menuNode, $level=0, $params, $itemHierarchy, $hasChildren = false) {
		$siteHelper = $this->getSiteHelper();
		$app = JFactory::getApplication();
		$isMobile = $app->getUserState('cmobile.ismobile', false);			
		$menuItemParametersString = (isset($menuNode->params) ? $menuNode->params : '');
		$menuItemParameters = FALSE;

		$txt = '';

		$gotFinalLink = FALSE;

		//print_r($menuNode);
		switch ($menuNode->type) {
			case 'separator':
				$menuNode->browserNav = 3;
				break;
			case 'component_item_link':
			case 'heading':
				$menuNode->link = "#";
				break;
			case 'content_item_link':
				if (!$this->hasItemid($menuNode->link)) {
					$temp = explode('&task=view&id=', $menuNode->link);
					if (count($temp) != 2) {
						$temp = explode('&view=article&id=', $menuNode->link);
					}
					if ((($this->callGetItemid) || ($menuNode->id === FALSE)) &&
							(count($temp) == 2)) {
						$contentId = $temp[1];
						if (function_exists('jimport')) {
							require_once(JPATH_SITE.'/components/com_content/helpers/route.php');
							$menuNode->link = ContentHelperRoute::getArticleRoute(
									$contentId, $menuNode->getCategoryId(), $menuNode->getSectionId());
							$gotFinalLink = FALSE;
							$id = FALSE;
						} else {
							global $Itemid, $mainframe; // only used if it is not Joomla 1.5+
							if ($menuNode->id !== FALSE) {
								$_Itemid	= $Itemid;
								$Itemid		= $menuNode->id;	// getItemid uses the global variable as a default value... use the id of the menu item instead
								$id			= $mainframe->getItemid($temp[1]);
								$Itemid		= $_Itemid;
							} else {
								$id			= $mainframe->getItemid($temp[1]);
							}
						}
					} else {
						$id	= $menuNode->id;
					}
					if ($id > 0) {
						$menuNode->link .= '&Itemid='.$id;
					}
				}
				break;
			case 'url':
				switch($this->addUrlItemidMode) {
					case 'local':
						$rootUri = $siteHelper->getUri();
						if ((strpos(strtolower($menuNode->link), 'index.php?') !== FALSE) &&
								(($rootUri == '') || (strpos($menuNode->link, ':') === FALSE) ||
										(strpos($menuNode->link, $rootUri) === 0))) {
							$menuNode->link		= $this->addItemid($menuNode->link, $menuNode->id);
						}
						break;
					case 'default':
					default:
						if (strpos(strtolower($menuNode->link), 'index.php?') !== FALSE) {
							$menuNode->link		= $this->addItemid($menuNode->link, $menuNode->id);
						}
				}
				break;
			case 'alias':
				// Joomla 1.6+
				if (!is_object($menuItemParameters)) {
					$menuItemParameters = $this->getParsedParameters($menuItemParametersString);
				}
				$menuNode->link		= 'index.php?Itemid='.$menuItemParameters->get('aliasoptions');
				break;
			case 'heading':
				$menuNode->link = "javascript:;";
			case 'content_typed':
			default:
				$menuNode->link		= $this->addItemid($menuNode->link, $menuNode->id);
				break;
		}
		// Active Menu highlighting
		// why reading the request parameter when there is a global variable?
//			$current_itemid = trim( mosGetParam( $_REQUEST, 'Itemid', 0 ) );

		// TODO should we really use the alias here?
		$thisItemParam = json_decode($menuNode->params); 
		$title	= strip_tags($thisItemParam->{'menu-anchor_title'});
		$anchor_css = strip_tags($thisItemParam->{'menu-anchor_css'});

		// use a more meaningful name than "id": elementParameters
		$elementParameters	= '';
		
		if ((isset($menuNode->accessKey)) && ($menuNode->accessKey != '')) {
			$elementParameters	.= ' accesskey="'.$menuNode->accessKey.'"';
			$title	.= ' ['.strtoupper($menuNode->accessKey).']';
		}

		if ($this->title) {
			$elementParameters	.= ' title="'.$title.'"';
		}

		if (!$gotFinalLink) {
			$shouldSefLink = ((strcasecmp(substr($menuNode->link, 0, 4), 'http') != 0) &&
					(strcasecmp(substr($menuNode->link, 0, 1), '#') != 0));
			if (class_exists('JRoute')) {
				if ($shouldSefLink) {
					if (!is_object($menuItemParameters)) {
						$menuItemParameters = $this->getParsedParameters($menuItemParametersString);
					}
					$secure = $menuItemParameters->def('secure', 0);
					$menuNode->link = JRoute::_($menuNode->link, true, $secure);
				} else {
					$menuNode->link = ampReplace($menuNode->link);
				}
			} else {
				$menuNode->link = ampReplace($menuNode->link);
				if ($shouldSefLink) {
					// no secure link support for older versions
					$menuNode->link = sefRelToAbs($menuNode->link);
				}
			}
		}

		$menuclass	= $this->getLinkMenuClassName($menuNode, $level);

		$linkBegin	= '';
		$linkText	= $menuNode->getCaption();
		$linkEnd	= '';

		if($hasChildren) {
			$menuclass .= " dropdown-toggle";
			if( ( !$isMobile ) && $level == 0 ) {
				if($menuNode->type == "heading") {
					$elementParameters .= ' data-toggle="dropdown" data-hover="dropdown"'; 
				} else {
					$elementParameters .= ' data-hover="dropdown"'; 
				}
			} else {
				$elementParameters .= ' data-toggle="dropdown"'; 
			}
			
			

		}

		switch ($menuNode->browserNav) {
			// cases are slightly different
			case 1:
				// open in a new window
				$linkBegin	= '<a href="'. $menuNode->link .'" target="_blank" class="'. $menuclass .' '. $anchor_css .'"'. $elementParameters .' itemprop="url">';
				$linkEnd	= '</a>';
//					$txt = '<a href="'. $menuNode->link .'" target="_blank" class="'. $menuclass .'"'. $elementParameters .'>'. $menuNode->name .'</a>';
				break;

			case 2:
				// open in a popup window
				$linkBegin	= "<a itemprop=\"url\" href=\"#\" onclick=\"javascript: window.open('". $menuNode->link ."', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550'); return false\" class=\"$menuclass\"". $elementParameters .">";
				$linkEnd	= "</a>\n";
//					$txt = "<a href=\"#\" onclick=\"javascript: window.open('". $menuNode->link ."', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550'); return false\" class=\"$menuclass\"". $elementParameters .">". $menuNode->name ."</a>\n";
				break;

			case 3:
				// don't link it
				$linkBegin	= '<span class="'. $menuclass .'"'. $elementParameters .'>';
				$linkText	= $menuNode->getCaption();
				if ($linkText == '') {
					$linkText = '&nbsp;';
				}
				$linkEnd	= '</span>';
//					$txt = '<span class="'. $menuclass .'"'. $elementParameters .'>'. ($menuNode->name != '' ? $menuNode->name : '&nbsp;') .'</span>';
				break;

			default:	// formerly case 2
				// open in parent window
				$linkBegin	= '<a href="'. $menuNode->link .'" title="'. $title .'" class="'. $menuclass .' '. $anchor_css .'"  '. $elementParameters .' itemprop="url">';
				$linkEnd	= '</a>';
//					$txt = '<a href="'. $menuNode->link .'" class="'. $menuclass .'"'. $elementParameters .'>'. $menuNode->name .'</a>';
				break;
		}

		$txt	= $linkBegin.$linkText.$linkEnd;

		if ($this->imageEnabled) {
			if (!is_object($menuItemParameters)) {
				$menuItemParameters = $this->getParsedParameters($menuItemParametersString);
			}
			$menu_image = $menuItemParameters->def('menu_image', -1);
			if ( ( $menu_image <> '-1' ) && $menu_image ) {
				$image = '<img src="'.$siteHelper->getUri(''.$menu_image).'" alt="'. $menuNode->getCaption() .'"/>';
				switch($this->imageAlignment) {
					case 'image_only':	// does not really make sense
						$txt	= $image;
						break;
					case 'image_only_linked':
						$txt	= $linkBegin.$image.$linkEnd;
						break;
					case 'right':
						$txt	= $txt.' '.$image;
						break;
					case 'right_linked':
						$txt	= $linkBegin.$linkText.' '.$image.$linkEnd;
						break;
					case 'left_linked':
						$txt	= $linkBegin.$image.' '.$linkText.$linkEnd;
						break;
					case 'left':
					default:
						$txt	= $image.' '.$txt;
				}
			}
		}

		$this->lastLinkBegin	= $linkBegin;
		$this->lastLinkEnd		= $linkEnd;

		return $txt;
	}

	function getHierarchyString($hierarchy) {
		if(is_array($hierarchy)) {
			$result	= implode('_', $hierarchy);
			if ($result == ' ') {
				$result	= 0;
			}
		} else {
			$result	= 0;
		}
		return $result;
	}

	function renderAsString($menuNodeList, $level = 0) {
		return $this->_renderMenuNodeList($menuNodeList, $level, $this->menuHierarchy);
	}

	function _renderMenuNodeList($menuNodeList, $level = 0, $hierarchy = array(), $noLineMap = NULL, $parentItemNumber = 0) {
		$siteHelper = $this->getSiteHelper();
		if (!is_array($noLineMap)) {
			$noLineMap = array();
		}
		
		$imagePath = $siteHelper->getSiteTemplateUri('images/');
		$result	= '';
		$keys = array_keys($menuNodeList);
		$count = count($keys);
		$iItem = 0;
		if ($level == 0) {
			$result	.= '<div class="clearfix nav-no-collapse">';
		}
		$result	.= '<ul';
		if ($level == 0) {
			$result	.= ' id="'.$this->idSuffix.'" class="'.$this->classSuffix.'" role="menu" itemscope itemtype="http://www.schema.org/SiteNavigationElement"'; 
			}
		else {
			$result	.= ' class="dropdown-menu"';
		}
		
		$result	.= '>';
		foreach($keys as $id) {
			$iItem++;
			$isLast	= ($iItem == $count);
			$lastSuffix	= ($isLast ? '_last' : '');
			$menuNode = $menuNodeList[$id];
			$thisLIID = $this->idSuffix.'-item-'.$parentItemNumber.'-'.$iItem.'-'.$this->getHierarchyString($itemHierarchy);
			$hasSubMenuItems = ($menuNode->hasChildren());
			$result	.= '<li id="'.$thisLIID.'" itemprop="name" class="menu-item';
			if ($menuNode->isCurrent()) {
				$result	.= ' active';
			}
			/*** Steve Fixes to Toms Shit ***/
			$openSubMenuItems = (($hasSubMenuItems) && ($level < $this->maxDepth) && ($menuNode->isExpanded()));
			/*** Steve Fixes to Toms Shit ***/
			/*** Toms shit ***/
			if (($level > 0) && $openSubMenuItems) {
				$result	.= ' dropdown dropdown-submenu';
			}
			/*** Toms shit ***/
			else if($hasSubMenuItems) {
				$result	.= ' dropdown';
			}
			$result .='"';
			$result	.= '>';
			
			$itemHierarchy = $hierarchy;
			$itemHierarchy[] = $iItem;
			$subMenuNodeList = $menuNode->getChildNodeList();
			/*
			if (($hasSubMenuItems) && ($level == 0)) {
			$result .='<a href="#" class="less">X</a><a href="#'.$thisLIID.'" class="more">Z</a>';
			}
			*/
			
			if ($menuNode->type	== 'separator') {
				$params = $menuNode->params;
				$params = json_decode($params);
				$class = "separator";
				if(!empty($params->{'menu-anchor_css'})) {
					$class .= " " . $params->{'menu-anchor_css'};
				}
				$linkOutput ='<span class="'.$class.'">'.$menuNode->getCaption().'</span>';
			} else if ($menuNode->type == 'heading') {
				$linkOutput = trim($this->mosGetMenuLink($menuNode, $level, $this->params, $itemHierarchy, $openSubMenuItems));
			} else {
				$linkOutput = trim($this->mosGetMenuLink($menuNode, $level, $this->params, $itemHierarchy, $openSubMenuItems));
			}
			
			$result	.= $linkOutput;
			
			if ($openSubMenuItems) {
				$result	.= $this->_renderMenuNodeList($subMenuNodeList, $level+1, $itemHierarchy, $noLineMap, $parentItemNumber.'-'.$iItem);
			}
			unset($noLineMap[$level]);
			$result	.= '</li>';
		}
		$result	.= '</ul>';
		if ($level == 0) {
			$result	.= '</div>';
		}
		return $result;
	}
}
?>