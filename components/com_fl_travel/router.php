<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

function fl_travelBuildRoute(&$query) {
	$segments = array();
	if (isset($query['view'])) {
		if($query['view'] == "detail") {
			$segments[] = "offer-detail";
		}
		unset($query['view']);
	}
	if (isset($query['id'])) {
		$segments[] = $query['id'];
		unset($query['id']);
	}
	if (isset($query['name'])) {
		$alias = trim(preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%-]/s', '', strtolower($query['name'])));
		$alias = str_replace(" ", "-", $alias);
		$alias = str_replace("--", "-", $alias);
		
		$segments[] = JFilterOutput::stringURLSafe($alias);
		unset($query['name']);
	}
	// if (isset($query['alias'])) {
		// $key = $query['alias'];
		// $segments[] = $key;
		// unset($query['alias']);
	// }
	// if (isset($query['item_category_id'])) {
		// $segments[] = $query['item_category_id'];
		// unset($query['item_category_id']);
	// }
	
	return $segments;
}

function fl_travelParseRoute($segments) {
	$vars = array();
	$vars['view'] = 'results';
	
	if(isset($segments[0])) {
		if($segments[0] == "offer:detail" && $segments[1] && $segments[2]) {
			$vars['view'] = "detail";
			$vars['offer_id'] = $segments[1];
			$vars['alias'] = $segments[2];
		}
		// $alias = str_replace(":", "-", $segments[0]);
		// if($alias) {
			// // Detail page
			// $vars['view'] = 'detail';
			// $vars['alias'] = $alias;
		// } else {
			// // Results with search variables
			// foreach($segments as $s) {
				// if($s == "save") {
					// $vars['view'] = 'save';
					// continue;
				// }
				// $vars['search'][] = str_replace(":", "-", $s);
			// }
		// }
	}
	
	return $vars;
}
