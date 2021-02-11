<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

function fl_itemsBuildRoute(&$query) {
	$segments = array();
	if (isset($query['view'])) {
		if($query['view'] == "save") {
			$segments[] = $query['view'];
		}
		unset($query['view']);
	}
	if (isset($query['item_id'])) {
		$key = $query['item_id'];
		if (isset($query['name'])) {
			$key .= "-" . str_replace(" ", "-", strtolower($query['name']));
			unset($query['name']);
		}
		$segments[] = $key;
		unset($query['item_id']);
	}
	if (isset($query['alias'])) {
		$key = $query['alias'];
		$segments[] = $key;
		unset($query['alias']);
	}
	if (isset($query['item_category_id'])) {
		$segments[] = $query['item_category_id'];
		unset($query['item_category_id']);
	}
	
	return $segments;
}

function fl_itemsParseRoute($segments) {
	$vars = array();
	$vars['view'] = 'results';
	
	if(isset($segments[0])) {
		$alias = str_replace(":", "-", $segments[0]);
		if($alias) {
			// Detail page
			$vars['view'] = 'detail';
			$vars['alias'] = $alias;
		} else {
			// Results with search variables
			foreach($segments as $s) {
				if($s == "save") {
					$vars['view'] = 'save';
					continue;
				}
				$vars['search'][] = str_replace(":", "-", $s);
			}
		}
	}
	
	return $vars;
}
