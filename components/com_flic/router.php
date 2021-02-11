<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

function flicBuildRoute( &$query )
{
		$isList = 0;
		
       	$segments = array();
       	if(isset($query['view']))
       	{
       			if($query['view'] == "detail")
				{
        			$segments[] = "view";
				}
                unset( $query['view'] );
       }
       if(isset($query['gallery']))
       {
                $segments[] = $query['gallery'];
                unset( $query['gallery'] );
       };
       if(isset($query['category']))
       {
       		foreach($query['category'] as $cat)
			{	
                $segments[] = $cat;
			}
			unset( $query['category'] );
       };
	   if(isset($query['start']))
       {
                $segments[] = $query['start'];
                unset( $query['start'] );
       }
	   if(isset($query['page']))
       {
                unset( $query['page'] );
       }
	   if(isset($query['limitstart'])) {
                unset( $query['limitstart'] );
	   }
       return $segments;
}

function flicParseRoute( $segments )
{
	$count = count($segments);
    $vars = array();
	$vars['view'] = 'results';
	
	foreach($segments as $segment) {
		if($segment == "view") {
			$vars['view'] = 'grid';
		} else if(is_numeric($segment)) {
			$vars['page'] = $segment;
		} else if($vars['view'] == 'detail' || $vars['view'] == 'grid' ) {
			$vars['gallery'] = $segment;
		} else {
			$vars['category'][] = $segment;
		}
	}
	
	return $vars;
}