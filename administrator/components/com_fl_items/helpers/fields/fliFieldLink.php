<?php

defined('_JEXEC') or die;

class FliFieldLink extends FliField
{
	public function __construct($data) {
		parent::__construct($data);
	}
	
	protected function getAdminInputField() {
		$output = "<select class='form-control' name='val-$this->name' id='$this->name'>";
        foreach($this->options as $opt) {
            $selected = "";
            if($opt->item_property_multi_id == $this->value) {
                $selected = "selected='selected'";
            }
            $output .= '<option '.$selected.' value="'.$opt->item_property_multi_id.'">';
                $output .= $opt->option;
            $output .= "</option>";
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
			
			foreach($thisLinks as $child) {
				if(!empty($child)) {
					if($show && $show != "both") {
						$return .= $child->buildTemplate("{".$show."}", $links, array());
					} else {
						if($thisTemplate) {
							$return .= $child->buildTemplate($thisTemplate, $links, array());
						} else {
							$return .= "Template missing: $templatePath";
						}
					}
				}
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
} 