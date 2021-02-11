<?php

defined('_JEXEC') or die;

class FliFieldMlink extends FliField
{
	public function __construct($data) {
		parent::__construct($data);
	}
	
	protected function getAdminInputField() {
		$selectedVals = explode(",", $this->value);
		$output .= "<input type='hidden' name='mlink-$this->name' value='0'>";
        foreach($this->options as $opt) {
            $selected = "";
			if(in_array($opt->item_property_multi_id, $selectedVals)) {
				$selected = "checked";
			}
			$output .= '<div class="clearfix"><label class="checkbox">';
				$output .= '<input style="position: absolute;" type="checkbox" name="mlink-'.$this->name.'[]" value="'.$opt->item_property_multi_id.'" ' . $selected . ' />' . $opt->option;
			$output .= "</label></div>";
        }
        $output .= "</div>";
        return $output;
	}
	
	public function output($show = "both", $links = array(), $templateBuilder = null) {
		$typeId = explode("-", $this->type);
		$typeId = $typeId[1];
		$linkIds = $this->value;
		$thisLinkIds = explode(",", $linkIds);
		
		if(count($thisLinkIds)) {
			$thisLinks = array();
			$sortByOrdering = array();
			foreach($thisLinkIds as $linkId) {
				$thisLinks[] = $links[$linkId];
				$sortByOrdering[] = $links[$linkId]->ordering;
			}
			array_multisort($sortByOrdering, SORT_ASC, $thisLinks);
			
			$templateName = "id-".$typeId."-links";
			$templatePath = JPATH_BASE."/components/com_fl_items/templates/" . $templateName . ".php";
			$thisTemplate = file_get_contents($templatePath);
			
			if($thisTemplate) {
				foreach($thisLinks as $child) {
					if(!empty($child)) {
						$return .= $child->buildTemplate($thisTemplate, $links);
					}
				}
			} else {
				return "Template missing: $templatePath";
			}
			return $return;
		}
					
		if(empty($this->value)) {
			return "";
		}
		$split = explode(",", $this->value);
		$cleanVal = "";
		
		if($show == "bothescape") {
	        return json_encode("<div class='item-property $this->name'><strong>$this->caption:</strong> $this->value</div>");
	    } else if($show == "caption") {
	        return $this->caption;
	    } else if($show == "name") {
	        return $this->name;
	    } else if($show == "value") {
	        return $this->value;
	    } else if($show == "escape") {
	        return json_encode($this->value);
	    } else {
			return "<div class='item-property $this->name'><strong>$this->caption:</strong> $this->value</div>";
		}
	}

	public function prepValueForSave($value) {
		if(is_array($value)) {
			$value = implode(",", $value);
		}
		
		return $value;
	}
} 