<?php

defined('_JEXEC') or die;

class FliFieldTextmultiple extends FliField
{
	public function __construct($data) {
		parent::__construct($data);
	}
	
	public function output($show = "both") {
		if(empty($this->value)) {
			return "";
		}
		
		if(strpos($this->value, "{[,}]") !== false) {
			$split = explode("{[,}]", $this->value);
			$this->value = "<div class='multi-text'>" . implode("</div><div class='multi-text'>", $split) . "</div>";
		}
		
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
	
	public function getAdminInputField() {
		$output = "<div class='multi-text-wrapper-$this->name'>";
		$split = explode("{[,}]", $this->value);
		foreach($split as $sv) {
			$output .= "
			<div class='multi-text-item-$this->name'>
				<input class='inputbox multi-text-$this->name' type='text' value=\"" . htmlspecialchars($sv) . "\"></input>
				<a href='javascript:;' class='btn btn-danger multi-text-remove-btn'>X</a>
			</div>";
		}
		$output .= "</div>";

		$output .= "
			<a href='javascript:;' class='btn btn-success multi-text-add-btn'>Add Additional Line</a>
			<input class='inputbox' type='hidden' name='val-$this->name' id='$this->name' value='$this->value'></input>
			<script>
				function updateField$this->name() {
					var newVals = [];
					jQuery('.multi-text-$this->name').each(function() {
						var thisVal = jQuery(this).val();
						if(thisVal.length) {
							newVals.push(thisVal);
						}
					});
					jQuery('#$this->name').val(newVals.join('{[,}]'));
				}
				jQuery('.multi-text-add-btn').click(function() {
					jQuery('.multi-text-wrapper-$this->name').append(".'"'."<div class='multi-text-item-$this->name'><input class='inputbox multi-text-$this->name' type='text' value=''></input> <a href='javascript:;' class='btn btn-danger multi-text-remove-btn'>X</a></div>".'"'.");
					updateField$this->name();
				});
				jQuery('.multi-text-wrapper-$this->name').on('click', '.multi-text-remove-btn', function() {
					jQuery(this).parent().remove();
					updateField$this->name();
				});
				jQuery('.multi-text-wrapper-$this->name').on('change', '.multi-text-$this->name', function() {
					updateField$this->name();
				});
				jQuery('.multi-add-wrapper .multi-text-$this->name').each(function() {
					jQuery(this).val();
				});
			</script>
		";
		return $output;
	}
} 