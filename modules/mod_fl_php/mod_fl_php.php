<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

// get module parameters
$php = $params->get( 'php' );
$eval_php = $params->get( 'eval_php' );

// remove annoying <br /> tags from module parameter
$php = str_replace('<br />', '', $php);

// evaluate the PHP code
if ($eval_php) {
	eval("\r\n?>\r\n ".$php."\r\n<?php\r\n");
} else {
	echo $php;
}



?>