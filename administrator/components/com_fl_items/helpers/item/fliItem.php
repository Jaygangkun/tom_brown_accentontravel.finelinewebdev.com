<?php

defined('_JEXEC') or die;

class FliItem
{
	private $templateBuilder = null;
	
	private $item_id = 0;
	private $item_category_id = 0;
	private $name = "";
	private $alias = "";
	private $isFeatured = 0;
	private $parent_item_id = 0;
	private $parent_item_variation_id = 0;
	private $category = "";
	private $menuId = 0;
	private $image = "";
	private $parentName = "";
	private $parentAlias = "";
	
	private $options = array();
	private $properties = array();
	private $images = array();
	private $children = array();
	
	public function __construct($data, $templateBuilder) {
		$this->item_id = $data['item_id'];
		$this->item_category_id = $data['item_category_id'];
		$this->name = $data['name'];
		$this->alias = $data['alias'];
		$this->isFeatured = $data['isFeatured'];
		$this->parent_item_id = $data['parent_item_id'];
		$this->parent_item_variation_id = $data['parent_item_variation_id'];
		$this->parentName = $data['parentName'];
		$this->parentAlias = $data['parentAlias'];
		$this->category = $data['category'];
		$this->menuId = $data['menuId'];
		$this->image = $data['image'];
		$this->options = $data['options'];
		
		$this->templateBuilder = $templateBuilder;
	}
	
	public function addProperty($type, $data) {
		if($this->options[$data->name]) {
			$data->options = $this->options[$data->name];
		}
		
		if(strpos($type, "-") !== false) {
			$split = explode("-", $type, 2);
			$type = $split[0];
			$linkId = $split[1];
		}
		$type = ucwords(strtolower($type));
		// Include correct property type file
		if(file_exists(JPATH_ADMINISTRATOR.'/components/com_fl_items/helpers/fields/fliField'.$type.'.php')) {
			include_once(JPATH_ADMINISTRATOR.'/components/com_fl_items/helpers/fields/fliField'.$type.'.php');
			$className = "FliField$type";
			$newField = new $className($data);

			$this->properties[$data->name] = $newField;
		} else if(file_exists(JPATH_ADMINISTRATOR.'/components/com_fl_items/helpers/customFields/fliField'.$type.'.php')) {
			include_once(JPATH_ADMINISTRATOR.'/components/com_fl_items/helpers/customFields/fliField'.$type.'.php');
			$className = "FliField$type";
			$newField = new $className($data);

			$this->properties[$data->name] = $newField;
		} else {
			echo "<div>Error: Custom class file required for type: " . $type . "</div>";
		} 
	}

	public function getProperty($name) {
		if($this->properties[$name]) {
			return $this->properties[$name];
		}
		return null;
	}
	
	public function getTemplateEntry($matches, $links, $categoryData) {
		$matchSplit = str_replace("{", "", str_replace("}", "", $matches[0]));
		$matchSplit = explode("-", $matchSplit, 3);
		$data1 = $matchSplit[0];
		if($matchSplit[1]) {
			$data2 = $matchSplit[1]; 
		}
		if($matchSplit[2]) {
			$data3 = $matchSplit[2];
		}
		switch ($data1) {
			case 'item':
				if($this->$data2) {
					return $this->$data2;
				}
				return "";
			case 'prop':
				if($data2 && $this->properties[$data2]) {
					return $this->properties[$data2]->output($data3, $links);
				}
				return "";
			case 'img':
				if(count($this->images)) {
					if($data2 == "gallery" && count($this->images) > 1) {
						return TemplateBuilderImages::getGallery($this->images);
					} else if($data2 == "gallerythumbs" && count($this->images) > 1) {
						return TemplateBuilderImages::getGalleryThumbs($this->images, $data3);
					} else if($data2 == "grid" && count($this->images) > 1) {
						return TemplateBuilderImages::getGridGallery($this->images, $data3);
					} else if($data2 == "gridlightbox" && count($this->images) > 1) {
						return TemplateBuilderImages::getGridGalleryLightbox($this->images, $data3);
					} else {
						return TemplateBuilderImages::getFirstImage($this->images, $this->item_category_id, $this->image, $this->item_id, $data3);
					}
				} else {
					return TemplateBuilderImages::getFirstImage($this->images, $this->item_category_id, $this->image, $this->item_id, $data3);
				}
				break;
			case 'url':
				if(isset($data2) && $data2 && is_numeric($data2)){
					$url = JRoute::_('index.php?Itemid='.$data2);
					return $url;
				}
				$name = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%-]/s', '', $this->name);
				if(!empty($this->menuId)) {
					$url = JRoute::_('index.php?Itemid='.$this->menuId.'&view=detail&alias='.$this->alias);
				} else {
					$url = JRoute::_('index.php?view=detail&alias='.$this->alias);
				}
				return $url;
				break;
			case 'type':
				if($categoryData->$data2) {
					return $categoryData->$data2;
				}
				return '';
				break;
			case 'children':
				if(count($this->children)) {
					$propertyId = $data2;
					$first = reset($this->children);
					$templateName = JFilterOutput::stringURLSafe($first->category)."-children";
					$templatePath = JPATH_BASE."/components/com_fl_items/templates/" . $templateName . ".php";
					$thisTemplate = file_get_contents($templatePath);
	
					if($thisTemplate) {
						$childCount = 0;
						foreach($this->children as $child) {
							$childCount++;
							if(!empty($child)) {
								$thisChildTemplate = $child->buildThisTemplate($thisTemplate, $childCount == count($this->children));
								$return .= $child->buildTemplate($thisChildTemplate, $links, $categoryData);
							}
						}
					} else {
						return "Missing Layout: $templateName.php";
					}
					return $return;
				} else {
					return "";
				}
				break;
			case 'addon':
				if(file_exists(JPATH_BASE."/components/com_fl_items/templates/addons/" . $data2 . ".php")) {
					$addon = file_get_contents(JPATH_BASE."/components/com_fl_items/templates/addons/" . $data2 . ".php");
					$addon = str_replace("<?php", "", $addon);
					return eval($addon);
				} else {
					return "<strong>Misson Addon File: " . $data2 . ".php</strong>";
				}
				break;
			case 'loadposition':
				$pos = substr($matches[0], strpos($matches[0], "-")+1, strlen($matches[0]) - strpos($matches[0], "-") - 2);
				$modules  = JModuleHelper::getModules($pos);
				
				$document = JFactory::getDocument();     
				$attribs  = array();
				$attribs['style'] = 'FLuid';
				
				$html = "";
				foreach ($modules as $mod)
				{
				    $html .= JModuleHelper::renderModule($mod, $attribs);
				}
				return $html;
				break;
			default:
				return $matches[0];
		}
		return $matches[0];
	}
	
	public function buildTemplate($template, $links, $categoryData) {
		$that = $this;
		return preg_replace_callback( "/{([^-\s][^-}]*)-?([^}]*)}/", 
		function($matches) use($that, $links, $categoryData) {
		    if(strpos($matches[2], "-") !== false) {
		        $split = explode("-", $matches[2]);
				$splitCount = 2;
				foreach($split as $s) {
					$matches[$splitCount] = $s;
					$splitCount++;
				}
		    }
		    return $that->getTemplateEntry($matches, $links, $categoryData);
		}, $template );
	}

	/**
 	* Check for IF statements
 	*/
	public function buildThisTemplate($currentTemplate, $isLast = 0) {
		while(strpos($currentTemplate, "{if-") !== false) {
			$ifOpenStart = strpos($currentTemplate, "{if-");
			$ifOpenEnd = strpos($currentTemplate, "}", $ifOpenStart);
			$ifElse = strpos($currentTemplate, "{else}", $ifOpenEnd);
			$ifEnd = strpos($currentTemplate, "{endif}", $ifOpenEnd);
			$nextIfStart = strpos($currentTemplate, "{if-", $ifOpenEnd);
			while($nextIfStart && $nextIfStart < $ifEnd) {
				$ifOpenStart = strpos($currentTemplate, "{if-", $ifOpenEnd);
				$ifOpenEnd = strpos($currentTemplate, "}", $ifOpenStart);
				$ifElse = strpos($currentTemplate, "{else}", $ifOpenEnd);
				$ifEnd = strpos($currentTemplate, "{endif}", $ifOpenEnd);
				$nextIfStart = strpos($currentTemplate, "{if-", $ifOpenEnd);
			}
			$ifProperty = substr($currentTemplate, $ifOpenStart + 4, $ifOpenEnd - $ifOpenStart - 4);
			
			$pass = false;
			$thisProperty = $this->getProperty($ifProperty);
			if($thisProperty) {
				$testValue = $thisProperty->getValue();
				if($testValue) {
					$pass = true;
				}
			}
			
			if($ifElse && $ifElse < $ifEnd) {
				// If there is an ELSE
				if(!$pass) {
					// Remove ENDIF
					$currentTemplate = substr_replace($currentTemplate, "", $ifEnd, 7);
					// Remove everything BEFORE the ELSE
					$currentTemplate = substr_replace($currentTemplate, "", $ifOpenStart, $ifElse - $ifOpenStart + 6);
				} else {
					// Remove just the IFs
					$currentTemplate = substr_replace($currentTemplate, "", $ifElse, $ifEnd - $ifElse + 7);
					$currentTemplate = substr_replace($currentTemplate, "", $ifOpenStart, $ifOpenEnd - $ifOpenStart + 1);
				}
			} else {
				// NO ELSE
				if(!$pass) {
					// Remove everything
					$currentTemplate = substr_replace($currentTemplate, "", $ifOpenStart, $ifEnd - $ifOpenStart + 7);
				} else {
					// Remove just the IFs
					$currentTemplate = substr_replace($currentTemplate, "", $ifEnd, 7);
					$currentTemplate = substr_replace($currentTemplate, "", $ifOpenStart, $ifOpenEnd - $ifOpenStart + 1);
				}
			}
		}

		// If Has Images
		while(strpos($currentTemplate, "{ifimg}") !== false) {
			$ifOpenStart = strpos($currentTemplate, "{ifimg}");
			$ifEnd = strpos($currentTemplate, "{endifimg}");
			
			if(count($this->images) == 0) {
				// Remove everything
				$currentTemplate = substr_replace($currentTemplate, "", $ifOpenStart, $ifEnd - $ifOpenStart + 10);
			} else {
				// Remove just the IFs
				$currentTemplate = substr_replace($currentTemplate, "", $ifEnd, 10);
				$currentTemplate = substr_replace($currentTemplate, "", $ifOpenStart, 7);
			}
		}
		
		// If NOT Last Item
		while(strpos($currentTemplate, "{ifnotlast}") !== false) {
			$ifOpenStart = strpos($currentTemplate, "{ifnotlast}");
			$ifEnd = strpos($currentTemplate, "{endifnotlast}");
			
			if($isLast) {
				// Remove everything
				$currentTemplate = substr_replace($currentTemplate, "", $ifOpenStart, $ifEnd - $ifOpenStart + 14);
			} else {
				// Remove just the IFs
				$currentTemplate = substr_replace($currentTemplate, "", $ifEnd, 14);
				$currentTemplate = substr_replace($currentTemplate, "", $ifOpenStart, 11);
			}
		}

		// If Has Children
		while(strpos($currentTemplate, "{ifhaschildren}") !== false) {
			$ifOpenStart = strpos($currentTemplate, "{ifhaschildren}");
			$ifEnd = strpos($currentTemplate, "{endifhaschildren}");
			
			if(!count($this->children)) {
				// Remove everything
				$currentTemplate = substr_replace($currentTemplate, "", $ifOpenStart, $ifEnd - $ifOpenStart + 18);
			} else {
				// Remove just the IFs
				$currentTemplate = substr_replace($currentTemplate, "", $ifEnd, 18);
				$currentTemplate = substr_replace($currentTemplate, "", $ifOpenStart, 15);
			}
		}
		
		return $currentTemplate;
	}
	
	public function addChild($child) {
		$this->children[] = $child;
	}

	public function setImages($images) {
		$this->images = $images;
	}
	
	public function getImageCount() {
		return count($this->images);
	}

	public function outputAdminForm() {
		foreach($this->properties as $p) {
			echo $p->outputAdminField();
		}
	}
	
	public function outputBatchAdminForm($c) {
		foreach($this->properties as $p) {
			echo $p->outputAdminBatchField($c);
		}
	}
	
	public function getParentName() {
		return $this->parentName;
	}
	
	public function getParentAlias() {
		return $this->parentAlias;
	}
	
	public function getItemId() {
		return $this->item_id;
	}
	
	public function getParentId() {
		return $this->parent_item_id;
	}
	
	public function getCategoryId() {
		return $this->item_category_id;
	}
	
	public function getAlias() {
		return $this->alias;
	}
	
	public function getMenuId() {
		return $this->menuId;
	}
	
	public function getChildren() {
		return $this->children;
	}
}