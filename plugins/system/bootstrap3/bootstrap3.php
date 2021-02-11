<?php
/** 
	* @version		$Id: bootstrap3.php 3 2014-07-30 $ 
	* @package		Joomla/Fine Line Websites 
	* @subpackage	Content * @copyright	Copyright (C) 2014 Fine Line Websites &amp; IT Consulting. All rights reserved. 
	* @license		GNU/GPL, see LICENSE.php
*/

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

// Set the separator as some idiot removed it from the core
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

jimport( 'joomla.plugin.plugin' );

class plgSystemBootstrap3 extends JPlugin {

	protected $bootstrap3 = null;
	
	function plgSystemBootstrap3 (&$subject, $config) {
		parent::__construct($subject, $config);
	}
	
	function onBeforeCompileHead () {
		$mainframe 		= JFactory::getApplication();
		if ($mainframe->isAdmin()) {
			return;
		}
		$doc = JFactory::getDocument();
		// remove the shits set by Joomla!
		foreach ( $doc->_scripts as $k => $array ) {
			if ( $k == '/media/jui/js/bootstrap.min.js' ) {
				unset($doc->_scripts[$k]);
			}
		}
		
	}
	
}