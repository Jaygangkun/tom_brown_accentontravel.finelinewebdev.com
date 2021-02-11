<?php

defined('_JEXEC') or die;

class FliFieldImage extends FliField
{
	public function __construct($data) {
		parent::__construct($data);
	}
	
	public function output($show = "both") {
		if(empty($this->value)) {
    		return "";
    	}
		$path = "/images/fl_items/files/" . $this->item_id . "/original/" . $this->value;
    	if($show == "path") {
    		return $path;
    	}
		if(exif_imagetype(JPATH_BASE.$path)) { // If this is an image show it ELSE return the path
    		return "<image src='$path'>";
		} else { 
			return $path;
		}
	}
	
	protected function getAdminInputField() {
		if(!$this->item_id) {
    		return "<em>To upload an image first click the \"Save\" button above.</em>";
    	}
    	$dimX = 0;
		$dimY = 0;
    	if($this->dimensions) {
			$split = explode(",", $this->dimensions);
			if(count($split) == 2) {
				$dimX = $split[0];
				$dimY = $split[1];
			}
		}
    	$output = "";
		$output .= '<div class="alert alert-danger pull-right" style="padding: 8px 14px;">';
    		$output .= '
    		<label class="checkbox" onchange="">
	      		<input type="checkbox" class="delete-file-checkbox"> Delete
		    </label>';
			$output .= '<input type="hidden" name="val-' . $this->name . '" id="val-' . $this->name . '" value="" disabled="disabled" />';
		$output .= '</div>';
		if($dimX && $dimY) {
			$output .= "<div class='small alert alert-info pull-right' style='padding: 8px 14px; margin-right: 8px;'>Dimensions: <strong>$dimX" . "px</strong>(wide) by <strong>$dimY" . "px</strong>(tall)</div>";
		}
		$output .= '<label style="margin-bottom: 5px;"><input class="inputbox hidden" type="file" name="file-' . $this->name . '" id="file-' . $this->name . '" /><div class="btn btn-primary btn-success">Browse New Image</div></span></label>';
    	$output .= '<div id="imgdrop-' . $this->name . '" class="img-upload-wrap">';
			$output .= '<div class="img-upload-inner">';
            	if(!empty($this->value)) {
            		if(strpos(strtolower($this->value), ".png") || strpos(strtolower($this->value), ".jpg") || strpos(strtolower($this->value), ".jpeg")) {
            			$output .= '<img id="thumb-' . $this->name . '" src="/images/fl_items/files/' . $this->item_id . "/original/" . $this->value . '" title="thumbnail"><br>';
            		}
            	} else {
            		$output .= '<img id="thumb-' . $this->name . '" src="/administrator/components/com_fl_items/assets/drop.png"><br>';
            	}
			$output .= '</div>';
		$output .= '</div>';
		$output .= '<div id="img-alert-' . $this->name . '" class="img-alert" style="display: inline-block;"></div>';
		
		$output .= "
		<script> 
			jQuery('#file-$this->name').on('change', function () {
				setDimension($dimX, $dimY);
				readFile(this); 
			});
			jQuery('#imgdrop-$this->name').on(
			    'drop',
			    function(e){
			        if(e.originalEvent.dataTransfer && e.originalEvent.dataTransfer.files.length) {
			        	jQuery('.img-upload-wrap').removeClass('hover').removeClass('on');
			            e.preventDefault();
			            e.stopPropagation();
						var thisInput = document.getElementById('file-$this->name');
						thisInput.files = e.originalEvent.dataTransfer.files;
						setDimension($dimX, $dimY);
			            readFile(thisInput);
			        }
			    }
			);
		</script>
		";
		return $output;
	}
} 