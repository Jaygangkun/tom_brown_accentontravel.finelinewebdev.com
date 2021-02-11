<?php

defined('_JEXEC') or die;

class FliFieldHeading extends FliField
{
	public function __construct($data) {
		parent::__construct($data);
	}

	protected function getAdminInputField() {
		$output = "<h3>$this->caption</h3>";
		
		return $output;
	}
	
	protected function wrapAdminInputField($field) {
		$output = "
		<tr>
			<td colspan='2'>
				$field
			</td>
		</tr>";
		
		return $output;
	}
} 