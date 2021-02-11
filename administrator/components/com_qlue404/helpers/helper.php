<?php

//no direct access
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.base.object');
jimport('joomla.application.component.helper');
jimport('joomla.language.language');
jimport('joomla.utilities.utility');

class Qlue404Helper extends JObject {

	/**
	 * Configure the Linkbar.
	 *
	 * @param   string	$vName	The name of the active view.
	 *
	 * @return  void
	 * @since   1.6
	 */
	public static function addSubmenu($vName)
	{
		JHtmlSidebar::addEntry(
			'Custom Error Page',
			'index.php?option=com_qlue404&view=customs',
			$vName == 'customs');
	}
		
	public static function getActions() {
			
		$user		= JFactory::getUser();
		$result		= new JObject();
		$assetName	= 'com_qlue404';

		$actions = array('core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete');

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
	
	public function sendMail($link, $hits, $component) {
		
		// Get global variable
		$mainframe = JFactory::getApplication();
		
		// Get our component params
		$params = new JRegistry;
		$params->loadJSON($component->params);
		
		// Check if we can send the email
		if( (int)$params->get('activate_email', 1) == 0 ) {
			 // Return
			 return;
		}
		
		// Get the email param
		$email = $params->get('email', '');
		
		// Check if email is not empty
		if( empty($email)) {
			// Return
			return;
		}
		
		// Check if email comma seperated
		$emails = strpos($email, ',') ? explode(',', $email) : (array)$email;
		
		// Get who mail is from
		$mailfrom = $mainframe->getCfg( 'mailfrom' );
		
		// Get who mail from name
		$fromname = $mainframe->getCfg( 'fromname' );
		
		// Get the site name
		$sitename = $mainframe->getCfg( 'sitename' );
		
		// Get language object
		$language = JFactory::getLanguage();
		
		// Load our components language file
		$language->load('com_qlue404');
		
		// Create the subject
		$subject = JText::sprintf( 'COM_QLUE404_EMAIL_BROKEN_LINK', $sitename);
		
		// Clean up the subject
		$subject = html_entity_decode($subject, ENT_QUOTES);
		
		// Create the message
		$message = JText::sprintf( 'COM_QLUE404_EMAIL_MESSAGE', $link, $hits);
		
		// Clean up the message
		$message = html_entity_decode($message, ENT_QUOTES);
		
		// Foreach email send them the notice
		foreach( $emails as $email ) {
			
			// Trim the email
			$email = trim($email);
			
			// Send the email
			JUtility::sendMail($mailfrom, $fromname, $email, $subject, $message, false);
			
		}
		
		// Return true on success
		return true;		
	}		
}
?>