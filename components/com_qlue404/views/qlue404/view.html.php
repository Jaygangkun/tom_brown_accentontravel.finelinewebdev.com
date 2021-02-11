<?php/** * Joomla! 1.5 component Qlue 404 * * @version $Id: view.html.php 2010-11-30 12:52:08 svn $ * @author Aaron Harding - Qlue * @package Joomla * @subpackage Qlue 404 * @license GNU/GPL * * Qlue 404 will detect all the major errors usually found on a website (404, 500) errors. This extension will allow you to custom these custom error pages with ease while still maintaining the proper error codes for seo purposes.  * */// no direct accessdefined('_JEXEC') or die('Restricted access');jimport( 'joomla.application.component.view');jimport( 'joomla.application.pathway');jimport( 'joomla.plugin.helper');jimport( 'joomla.registry.registry');/** * HTML View class for the Qlue 404 component */class Qlue404ViewQlue404 extends JViewLegacy {		function display($tpl = null) {					// Get instance of JApplication		$this->mainframe = JFactory::getApplication();				// Create an error variable		$error = null;				// If error code and error message is defined create our object		if(defined('QLUE_ERROR_CODE') && defined('QLUE_ERROR_MESSAGE')) {						// Create an error object			$error = new stdClass();						// Set the error code			$error->code = (int)QLUE_ERROR_CODE;						// Set the error message			$error->message = QLUE_ERROR_MESSAGE;		}				// Lets load the data for our custom page		$item = $this->get('Item');				// Check for errors.        if (count($errors = $this->get('Errors'))) {        	JError::raiseError(500, implode('<br />', $errors));            return false;        }				// Lets get our items params		$registry = new JRegistry();		$registry->loadString($item->params);				// import all content plugins		JPluginHelper::importPlugin('content');				// get list of avaliable plugins		$dispatcher = JDispatcher::getInstance();				// process the content through all content plugins		$results = $dispatcher->trigger( 'onContentPrepare', array('com_qlue404.error', &$item, &$params, 0));				// Assign Variables to layout		$this->item = $item;		$this->params = $registry;		$this->error = $error;				// Display our layout        parent::display($tpl);                // Set the document        $this->setDocument();    }        function setDocument() {    		    	// Get the document object		$document = JFactory::getDocument();				// Get pathway object		$pathway = $this->mainframe->getPathway();				// Set the base tag of the website. Helps prevent incorrect file sources		$document->setBase( JURI::base());				// Decide what the page title/ breadcrumb link will be		$title =  ($this->params->get('show_title', 1) ? $this->escape($this->item->title) : $this->escape(QLUE_ERROR_CODE .' '. QLUE_ERROR_MESSAGE) );				// Set the page title		$document->setTitle( $title);				// Change meta data so robots do not index this page		$document->setMetaData('robots', 'noindex, follow');				// Add this page to breadcrumbs if available		$pathway->addItem( $title);    }	}?>