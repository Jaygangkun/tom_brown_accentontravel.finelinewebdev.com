<?
defined('_JEXEC') or die;

class TemplateBuilder
{
	private $items = array();
	private $links = array();
	private $category = null; 
	private $options = array();
	
	public function __construct() {
		$language = JFactory::getLanguage();
		$language->load('com_fl_items', JPATH_ADMINISTRATOR, 'en-US', true);
	}
	
	public function addItems($items) {
		foreach($items as $i) {
			if($this->options) {
				$i['item']['options'] = $this->options;
			}
			$thisItem = new FliItem($i['item'], null);
			foreach($i['property'] as $p) {
				$propOptions = array();
				if($this->options[$p->name]) {
					$propOptions = $this->options[$p->name];
				}
				$thisItem->addProperty($p->type, $p, $propOptions);
			}
			$this->items[$i['item']['item_id']] = $thisItem;
		}
	}
	
	public function addImages($images) {
		foreach($images as $k=>$i) {
			if($this->items[$k]) {
				$this->items[$k]->setImages($i);
			}
		}
	}
	
	public function addLinks($links) {
		foreach($links as $i) {
			$thisItem = new FliItem($i['item'], null);
			foreach($i['property'] as $p) {
				$thisItem->addProperty($p->type, $p);
			}
			$this->links[$i['item']['item_id']] = $thisItem;
		}
	}
	
	public function setCategory($data) {
		$this->category = $data;
	}
	
	public function addChildren($children, $childrenImages = array()) {
		foreach($children as $parentId => $childrenList) {
			foreach($childrenList as $cId => $i) {
				$thisItem = new FliItem($i['item'], null);
				foreach($i['property'] as $p) {
					$thisItem->addProperty($p->type, $p);
				}
				if($childrenImages[$cId]) {
					$thisItem->setImages($childrenImages[$cId]);
				}
				$this->items[$parentId]->addChild($thisItem);
			}
		}
	}
	
	public function addOptions($options) {
		$this->options = $options;
	}
	
	public function buildTemplate($template) {
		$searchStart = 0;
		if(strpos($template, "{loopstart}") !== false) {
			while(strpos($template, "{loopstart}") !== false) {
				$loopAt = strpos($template, "{loopstart}", $searchStart);
				$loopEnd = strpos($template, "{loopend}", $loopAt);
				$loopTempate = substr($template, $loopAt + 11, $loopEnd - $loopAt - 11);
				$loopReturn = "";
				$itemCount = 0;
				$totalItems = count($this->items);
				foreach($this->items as $item) {
					$thisTemplate = $item->buildThisTemplate($loopTempate, $itemCount == $totalItems - 1 );
					$itemCount++;
					if(!empty($item)) {
						$loopReturn .= $item->buildTemplate($thisTemplate, $this->links, $item->getImageCount());
					}
				}
				$searchStart = $loopEnd - 9 - 11 - ($loopEnd - $loopAt - 11);
				$template = substr_replace($template, "", $loopEnd, 9);
				$template = substr_replace($template, $loopReturn, $loopAt + 11, $loopEnd - $loopAt - 11);
				$template = substr_replace($template, "", $loopAt, 11);
			}
		} else {
			foreach($this->items as $item) {
				$thisTemplate = $item->buildThisTemplate($template, true);
				$itemCount++;
				if(!empty($item)) {
					$template = $item->buildTemplate($thisTemplate, $this->links, $this->category);
				}
			}
		}
		$template = $item->buildTemplate($template, $this->links, $this->category);
		
		return $template;
	}
	
	
	private function buildValue($value, $caption = "", $show = "both", $name = "" ) {
		if(empty($value)) {
			return "";
		}
		if($show == "both") {
	        return "<div class='item-property $name'><strong>$caption:</strong> $value</div>";
		} else if($show == "bothescape") {
	        return json_encode("<div class='item-property $name'><strong>$caption:</strong> $value</div>");
	    } else if($show == "caption") {
	        return $caption;
	    } else if($show == "name") {
	        return $name;
	    } else if($show == "value") {
	        return $value;
	    } else if($show == "escape") {
	        return json_encode($value);
	    } else {
	        return "<div class='item-property $name'><strong>$caption:</strong> $value</div>";
		}
	}
}
