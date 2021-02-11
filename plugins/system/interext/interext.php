<?php
/**
 * InterExt Plugin
 * Joomla! Version 2.5
 * Author: Chris Burgess
 * chris.burgess@acuit.com.au
 * http://www.acuit.com.au
 * Copyright (c) 2011 AcuIT. All Rights Reserved. 
 * License: GNU/GPL 2, http://www.gnu.org/licenses/gpl-2.0.html
 */
 
defined( '_JEXEC' ) or die('Direct Access to this location is not allowed.');

jimport( 'joomla.plugin.plugin' );

class plgSystemInterExt extends JPlugin
{
	// 1.1 - added lazy quantifier to [^>]* to improve performance (in theory) as there will be less backtracking
	
	static $linkRegexStart = '%<link[^>]*?href\s*=\s*([\'"])';
	static $linkRegexEnd = '\\1[^>]*>\s*%';
	
	static $scriptRegexStart = '%<script[^>]*?src\s*=\s*([\'"])';
	static $scriptRegexEnd = '\\1[^>]*>.*?</script>\s*%';
	
	// 1.1
	static $styleRegex = '%<style[^>]*>.*?</style>\s*%s';
	
	// 1.1
	static $customScriptRegex = '%<script(?![^>]*src[^>]*>)[^>]*>.*?</script>\s*%s';
	
	/*
		Recognised jquery file formats ('x' is any digit 0-9):
		jquery.js
		jquery-x.x.js
		jquery.x.x.js
		jquery-x.x.x.js
		jquery.x.x.x.js
		jquery-latest.js
		jquery.latest.js
		plus all of the above ending in .min.js and .pack.js
	*/
	static $jQueryRegex = '%<script[^>]*?src\s*=\s*([\'"])[^=>]*?/jquery(?:[-\.](?:[0-9]\.[0-9](?:\.[0-9])?|latest))?(?:\.min|\.pack)?.js\\1[^>]*>.*?</script>\s*%';
	
	//static $mootoolsRegex = '%<script[^>]*?src\s*=\s*([\'"])[^=>]*?/media/system/js/mootools(?:[-\.]core(?:[-\.]uncompressed)?)?.js\\1[^>]*>.*?</script>\s*%';
	
	static $mootoolsCore = '/media/system/js/mootools-core.js';
	static $mootoolsCoreUnCompressed = '/media/system/js/mootools-core-uncompressed.js';
	
	//static $mootoolsMoreRegex = '%<script[^>]*?src\s*=\s*([\'"])[^=>]*?/media/system/js/mootools(?:[-\.]more(?:[-\.]uncompressed)?)?.js\\1[^>]*>.*?</script>\s*%';
	
	static $mootoolsMore = '/media/system/js/mootools-more.js';
	static $mootoolsMoreUnCompressed = '/media/system/js/mootools-more-uncompressed.js';
	
	//static $joomlaMootoolsLibRegexP1 = '%<script[^>]*?src\s*=\s*([\'"])';
	//static $joomlaMootoolsLibRegexP2 = '/media/system/js/(?:calendar|calendar-setup|caption|combobox|modal|mootree|multiselect|progressbar|swf|switcher|tabs|uploader|validate).js\\1[^>]*>.*?</script>\s*%';
	
	static $jLibsPath = '/media/system/js/';
	static $calendar1 = 'calendar.js';
	static $calendar1UC = 'calendar-uncompressed.js';
	static $calendar2 = 'calendar-setup.js';
	static $calendar2UC = 'calendar-setup-uncompressed.js';
	static $caption = 'caption.js';
	static $captionUC = 'caption-uncompressed.js';
	static $combobox = 'combobox.js';
	static $comboboxUC = 'combobox-uncompressed.js';
	static $modal = 'modal.js';
	static $modalUC = 'modal-uncompressed.js';
	static $mootree = 'mootree.js';
	static $mootreeUC = 'mootree-uncompressed.js';
	static $multiselect = 'multiselect.js';
	static $multiselectUC = 'multiselect-uncompressed.js';
	static $progressbar = 'progressbar.js';
	static $progressbarUC = 'progressbar-uncompressed.js';
	static $swf = 'swf.js';
	static $swfUC = 'swf-uncompressed.js';
	static $switcher = 'switcher.js';
	static $switcherUC = 'switcher-uncompressed.js';
	static $tabs = 'tabs.js';
	static $tabsUC = 'tabs-uncompressed.js';
	static $uploader = 'uploader.js';
	static $uploaderUC = 'uploader-uncompressed.js';
	static $validate = 'validate.js';
	static $validateUC = 'validate-uncompressed.js';
	
	static $JCaptionRegex = '%window\.addEvent\(\'load\',\s*function\(\)\s*{\s*new\s*JCaption\(\'img.caption\'\);\s*}\);\s*%';
	static $JCaptionRegexJQuery = "%jQuery\(window\)\.on\(\'load\',\s*function\(\)\s*\{\s*new\s*JCaption\('[^']*'\);\s*}\);%";
	static $JTooltipRegex = '%window\.addEvent\(\'domready\',\s*function\(\)\s*{\s*\$\$\(\'\.hasTip\'\)\.each\(function\(el\)\s*{[^}]+}\s*}\);\s*var\s+JTooltips\s+=\s+new\s+Tips\(\$\$\(\'\.hasTip\'\),\s*{[^}]+}\);\s*}\);\s*%';
	
	protected $isSite = false;
	protected $processDocument = false;
	
	protected $resources = array();
	
	protected $checkForOverlaps = true;
	
	protected $initTime = 0;
	protected $onBeforeRenderTime = 0;
	protected $onAfterRenderTime = 0;
	protected $totalTime = 0;
	
	protected $jQueryAction = 0;
	protected $jQueryMenuItems = array();
	protected $jQueryURL = '';
	protected $jQueryNoMenuItems = false;
	protected $jQueryNoConflict = true;
	
	protected $processGroups = false;
	
	/*
		Plugin constructor. Reads in the plugin's parameters and build the data structure for storage.
		Added: 1.0
		Changed:	1.1	- added checkForOverlaps and action option retreival
	*/
	function plgSystemInterExt(&$subject, $config)
	{
		parent::__construct($subject, $config);
	
		$app =& JFactory::getApplication();
		$doc =& JFactory::getDocument();
		$this->isSite = $app->isSite();
		
		$this->processDocument = $this->isSite && ($doc->getType() == 'html');
		
		if (!$this->processDocument) return;
	
		$this->displayTime = (bool)$this->params->get('displayTime');
		if ($this->displayTime)
		{
			$startInitTime = microtime(true);
			$this->totalTime = 0;
		}
	
		$this->checkForOverlaps = (bool)$this->params->get('checkForOverlaps');
		
		// mootools settings
		$this->mootoolsAction = (int)$this->params->get('mootoolsAction');
		$this->mootoolsException = (int)$this->params->get('mootoolsException');	// 0 - none, 1 - logged in, 2 - can edit
		if ($this->mootoolsAction >= 2 || ($this->mootoolsAction != 0 && $this->mootoolsException != 0))
		{
			$mtComp = (bool)$this->params->get('mootoolsCompressed');
			$mtVersion = $this->params->get('mootoolsVersion');
			switch($this->params->get('mootoolsLocation'))
			{
				case 1:
					if ($mtComp)
						$this->mootoolsURL = JURI::root(true)."/plugins/system/interext/mootools/mootools-$mtVersion.js";
					else
						$this->mootoolsURL = JURI::root(true)."/plugins/system/interext/mootools/mootools-$mtVersion-uncompressed.js";
					break;
				case 2:
					if ($mtComp)
						$this->mootoolsURL = "https://ajax.googleapis.com/ajax/libs/mootools/$mtVersion/mootools-yui-compressed.js";
					else
						$this->mootoolsURL = "https://ajax.googleapis.com/ajax/libs/mootools/$mtVersion/mootools.js";
					break;
				case 0:
				default:
					if ($mtComp)
						$this->mootoolsURL = JURI::root(true) . self::$mootoolsCore;
					else
						$this->mootoolsURL = JURI::root(true) . self::$mootoolsCoreUnCompressed;
					break;
			}
			
			$mootoolsMenuItems =& $this->params->get('mootoolsMenuItems');
			if ($mootoolsMenuItems != null)
			{
				$this->mootoolsNoMenuItems = false;
				foreach ($mootoolsMenuItems as $menuitem)
					$this->mootoolsMenuItems[$menuitem] = $menuitem;
			}
			else
			{
				$this->mootoolsNoMenuItems = true;
			}
		}
		$this->stripJoomlaMootoolsLibs = intval($this->params->get('stripJoomlaMootoolsLibs'));
		$this->stripJCaption = intval($this->params->get('stripMootoolsJCaption'));
		$this->stripJTooltip = intval($this->params->get('stripMootoolsJTooltip'));
		
		//echo "MooTools Options" . (microtime(true) - $startInitTime);
		//$progTime = microtime(true);
		
		// mootools more settings
		$this->mootoolsMoreAction = (int)$this->params->get('mootoolsMoreAction');
		$this->mootoolsMoreException = (int)$this->params->get('mootoolsMoreException');	// 0 - none, 1 - logged in, 2 - can edit
		if ($this->mootoolsMoreAction >= 2 || ($this->mootoolsMoreAction != 0 && $this->mootoolsMoreException != 0))
		{
			$mtmOption = (int)$this->params->get('mootoolsMoreOption');
			
			switch($mtmOption)
			{
				case 1:
					$this->mootoolsMoreURL = JURI::root(true)."/media/system/js/mootools-more-uncompressed.js";
					break;
				case 2:
					$this->mootoolsMoreURL = $this->params->get('mootoolsMoreCustomURL');
					break;
				case 0:
				default:
					$this->mootoolsMoreURL = JURI::root(true)."/media/system/js/mootools-more.js";
					break;
			}
			
			$mootoolsMoreMenuItems =& $this->params->get('mootoolsMoreMenuItems');
			if ($mootoolsMoreMenuItems != null)
			{
				$this->mootoolsMoreNoMenuItems = false;
				foreach ($mootoolsMoreMenuItems as $menuitem)
					$this->mootoolsMoreMenuItems[$menuitem] = $menuitem;
			}
			else
			{
				$this->mootoolsMoreNoMenuItems = true;
			}
		}
		
		//echo "MooTools More Options" . (microtime(true) - $progTime);
		//$progTime = microtime(true);
		
		// jQuery settings
		$this->jQueryAction = (int)$this->params->get('jQueryAction');
		if ($this->jQueryAction >= 2)
		{
			switch((int)$this->params->get('jQueryFileType'))
			{
				case 1:
					$jQueryFileEnding = '';
					break;
				case 0:
				default:
					$jQueryFileEnding = '.min';
					break;
			}
			$jQueryVersion = $this->params->get('jQueryVersion');
			switch((int)$this->params->get('jQueryServer'))
			{
				case 1:
					$this->jQueryURL = "http://ajax.googleapis.com/ajax/libs/jquery/{$jQueryVersion}/jquery{$jQueryFileEnding}.js";
					break;
				case 2:
					$this->jQueryURL = "http://code.jquery.com/jquery-{$jQueryVersion}{$jQueryFileEnding}.js";
					break;
				case 3:
					$this->jQueryURL = "http://ajax.aspnetcdn.com/ajax/jQuery/jquery-{$jQueryVersion}{$jQueryFileEnding}.js";
					break;
				case 0:
				default:
					$this->jQueryURL = JURI::root(true) . "/plugins/system/interext/jQuery/jquery-{$jQueryVersion}{$jQueryFileEnding}.js";
					break;
			}
			
			$jQueryMenuItems =& $this->params->get('jQueryMenuItems');
			if ($jQueryMenuItems != null)
			{
				$this->jQueryNoMenuItems = false;
				foreach ($jQueryMenuItems as $menuitem)
					$this->jQueryMenuItems[$menuitem] = $menuitem;
			}
			else
			{
				$this->jQueryNoMenuItems = true;
			}
			$this->jQueryNoConflict = (bool) $this->params->get('jQueryNoConflict');
		}
		
		//echo "jQuery Options" . (microtime(true) - $progTime);
		//$progTime = microtime(true);
		
		// the condition is just to prevent infinite loop in case of error, should never happen
		for ($group = 1; $group < 20; $group++)
		{
			$groupStr = "group$group";
			if (!$this->params->exists($groupStr."exists"))
				break;
				
			$this->resources[$group]['position'] = (int)$this->params->get($groupStr.'position');
			$this->resources[$group]['action'] = (int)$this->params->get($groupStr.'action');
			
			$this->resources[$group]['assignment'] = (int)$this->params->get($groupStr.'assignment');
			// convert the array of menu item id's to an associative array with item id as both key and value
			// so that way we can check if current item is in the list by a simple isset on the key, which
			// should guard against performance degradation for higher number of selected menu items
			$menuitems =& $this->params->get($groupStr.'menuitems');
			
			if ($menuitems != null)
			{
				foreach ($menuitems as $menuitem)
					$this->resources[$group]['menuitems'][$menuitem] = $menuitem;
			}
			
			for ($res = 1; $res < 10; $res++)
			{
				$resStr = $groupStr."res$res";
				if (!$this->params->exists($resStr))
					break;
					
				$this->processGroups = true;
				
				$resData = $this->params->get($resStr);
				if ($resData != '')
				{
					$this->resources[$group]['resources'][$res]['data'] = $resData;
					$this->resources[$group]['resources'][$res]['type'] = (int)$this->params->get($resStr.'type');
				}
			}
		}
		
		//echo "Group Options" . (microtime(true) - $progTime);
		//$progTime = microtime(true);
		
		if ($this->displayTime)
		{
			$this->initTime = microtime(true) - $startInitTime;
			$this->totalTime += $this->initTime;
		}
	}
	
	function onBeforeRender()
	{
		if (!$this->processDocument) return;
		
		if ($this->displayTime)
			$startTime = microtime(true);
		
		$doc =& JFactory::getDocument();
		
		$this->item_id = -1;
		$app =& JFactory::getApplication();
		$menu	= $app->getMenu();
		$active	= $menu->getActive();
		$this->item_id = isset($active) ? $active->id : $menu->getDefault()->id;
		
	//echo $this->item_id;
		
		$exceptLoggedIn = false;
		$exceptCanEdit = false;
			
		// check if user logged in, and if can edit, to handle mootools exception
		if ($this->mootoolsException != 0 || $this->mootoolsMoreException != 0)
		{
			$user = JFactory::getUser();
			if (!$user->get('guest'))
			{
				$exceptLoggedIn = true;
				// code adapted from components/com_content/models/article.php, which also checks if user owns item
				// this is just general edit permissions
				$userId	= $user->get('id');
				$pk = JRequest::getInt('id');
				$asset	= 'com_content.article.'.$pk;
				if ($user->authorise('core.edit', $asset))
				{
					$exceptCanEdit = true;
				}
				else if (!empty($userId) && $user->authorise('core.edit.own', $asset)) 
				{
					// see if the user owns the article from the db
					$db =& JFactory::getDBO();
					$query = $db->getQuery(true);
					$query->select('id, created_by');
					$query->from('#__content');
					$query->where('id = ' . (int) $pk);
					$db->setQuery($query);
					$data = $db->loadObject();
					// Check for a valid user and that they are the owner.
					if (!empty($data) && $userId == $data->created_by)
					{
						$exceptCanEdit = true;
					}
				}
			}
		}
		
		$this->removeMootools = $this->mootoolsAction > 0;
		$this->addMootools = ($this->mootoolsAction > 0 && 
											(
												$this->mootoolsAction === 2 && isset($this->mootoolsMenuItems[$this->item_id]) || 
												$this->mootoolsAction === 3 && !isset($this->mootoolsMenuItems[$this->item_id]) || 
												$this->mootoolsException === 1 && $exceptLoggedIn ||
												$this->mootoolsException === 2 && $exceptCanEdit
											)
										);
		
		$mootoolsMoreExists = isset($doc->_scripts[JURI::root(true) . self::$mootoolsMore]) || isset($doc->_scripts[JURI::root(true) . self::$mootoolsMoreUnCompressed]);
		
		$this->removeMootoolsMore = $this->mootoolsMoreAction > 0;
		$this->addMootoolsMore = ($this->mootoolsMoreAction > 0 && $this->addMootools &&
										(
											$this->mootoolsMoreAction === 2 && isset($this->mootoolsMoreMenuItems[$this->item_id]) || 
											$this->mootoolsMoreAction === 3 && !isset($this->mootoolsMoreMenuItems[$this->item_id]) ||
											(
												$mootoolsMoreExists && 
												(
													$this->mootoolsMoreException === 1 && $exceptLoggedIn || 
													$this->mootoolsMoreException === 2 && $exceptCanEdit
												)
											)
										)
									);
		
		$this->removeJLibs = $this->stripJoomlaMootoolsLibs == 1 || ($this->stripJoomlaMootoolsLibs == 2 && $this->removeMootools && !$this->addMootools);
		$this->removeJCaption = $this->stripJCaption == 1 || ($this->stripJCaption == 2 && $this->removeMootools && !$this->addMootools);
		$this->removeJTooltip = $this->stripJTooltip == 1 || ($this->stripJTooltip == 2 && ((!$mootoolsMoreExists && !$this->addMootoolsMore) || ($mootoolsMoreExists && $this->removeMootoolsMore && !$this->addMootoolsMore)));
	
		$this->removeJQuery = $this->jQueryAction > 0;
		$this->addJQuery = ($this->jQueryAction === 2 && isset($this->jQueryMenuItems[$this->item_id])) || 
										($this->jQueryAction === 3 && !isset($this->jQueryMenuItems[$this->item_id]));
		
	//echo $this->addJQuery ? 'add jquery' : 'not add jquery';
	//print_r($this->jQueryMenuItems);
		
		if ($this->removeMootools)
		{
			unset($doc->_scripts[JURI::root(true) . self::$mootoolsCore]);
			unset($doc->_scripts[JURI::root(true) . self::$mootoolsCoreUnCompressed]);
		}
		
		if ($this->removeMootoolsMore)
		{
			unset($doc->_scripts[JURI::root(true) . self::$mootoolsMore]);
			unset($doc->_scripts[JURI::root(true) . self::$mootoolsMoreUnCompressed]);
		}
		
		// strip mootools libs
		$jLibsPathRoot = JURI::root(true) . self::$jLibsPath;
		if ($this->removeJLibs)
		{
			unset($doc->_scripts[$jLibsPathRoot . self::$calendar1]);
			unset($doc->_scripts[$jLibsPathRoot . self::$calendar1UC]);
			unset($doc->_scripts[$jLibsPathRoot . self::$calendar2]);
			unset($doc->_scripts[$jLibsPathRoot . self::$calendar2UC]);
			unset($doc->_scripts[$jLibsPathRoot . self::$combobox]);
			unset($doc->_scripts[$jLibsPathRoot . self::$comboboxUC]);
			unset($doc->_scripts[$jLibsPathRoot . self::$modal]);
			unset($doc->_scripts[$jLibsPathRoot . self::$modalUC]);
			unset($doc->_scripts[$jLibsPathRoot . self::$mootree]);
			unset($doc->_scripts[$jLibsPathRoot . self::$mootreeUC]);
			unset($doc->_scripts[$jLibsPathRoot . self::$multiselect]);
			unset($doc->_scripts[$jLibsPathRoot . self::$multiselectUC]);
			unset($doc->_scripts[$jLibsPathRoot . self::$progressbar]);
			unset($doc->_scripts[$jLibsPathRoot . self::$progressbarUC]);
			unset($doc->_scripts[$jLibsPathRoot . self::$swf]);
			unset($doc->_scripts[$jLibsPathRoot . self::$swfUC]);
			unset($doc->_scripts[$jLibsPathRoot . self::$switcher]);
			unset($doc->_scripts[$jLibsPathRoot . self::$switcherUC]);
			unset($doc->_scripts[$jLibsPathRoot . self::$tabs]);
			unset($doc->_scripts[$jLibsPathRoot . self::$tabsUC]);
			unset($doc->_scripts[$jLibsPathRoot . self::$uploader]);
			unset($doc->_scripts[$jLibsPathRoot . self::$uploaderUC]);
			unset($doc->_scripts[$jLibsPathRoot . self::$validate]);
			unset($doc->_scripts[$jLibsPathRoot . self::$validateUC]);
		}
		
		if ($this->removeJCaption)
		{
			unset($doc->_scripts[$jLibsPathRoot . self::$caption]);
			unset($doc->_scripts[$jLibsPathRoot . self::$captionUC]);
			if (isset($doc->_script['text/javascript']))
			{
				$doc->_script['text/javascript'] = preg_replace(self::$JCaptionRegex, '', $doc->_script['text/javascript']);
				$doc->_script['text/javascript'] = preg_replace(self::$JCaptionRegexJQuery, '', $doc->_script['text/javascript']);
				if (empty($doc->_script['text/javascript']))
					unset($doc->_script['text/javascript']);
			}
		}
		
		if ($this->removeJTooltip && isset($doc->_script['text/javascript']))
		{
			$doc->_script['text/javascript'] = preg_replace(self::$JTooltipRegex, '', $doc->_script['text/javascript']);
			if (empty($doc->_script['text/javascript']))
				unset($doc->_script['text/javascript']);
		}
		
		if ($this->displayTime)
		{
			$this->onBeforeRenderTime = microtime(true) - $startTime;
			$this->totalTime += $this->onBeforeRenderTime;
		}
	}
	
	/*
		The algorithm for processing the head data and sorting the tags, added to the onAfterRender event.
		Added: 1.0
		Changed:	1.1	- added options style, script - block, custom text, regex to switch
								- moved $match and $offset getting to within case as different behvaiour in some different options
								- added check for overlaps
								- added code within gropu to handle unique option, not just sort
								- added timing code
	*/
	function onAfterRender()
	{
		if (!$this->processDocument) return;
		
		if ($this->displayTime)
			$startTime = microtime(true);
	
		/*********
		
			Retrieve current page content
			
		**********/
		
		$content = JResponse::getBody();
		
		// check that this is a full html page that should be processed
		$htmlTagIndex = strpos($content, '<html');
		if (!$htmlTagIndex)
			return;
		$headTagIndex = strpos($content, '<head', $htmlTagIndex);
		if (!$headTagIndex)
			return;
		$endHeadTagIndex = strpos($content, '</head>', $headTagIndex);
		if (!$endHeadTagIndex)
			return;
		$bodyTagIndex = strpos($content, '<body', $endHeadTagIndex);
		if (!$bodyTagIndex)
			return;
		
		// find the end title tag, up to the start of something not whitespace
		$hasTitle = preg_match("%</title>\s*%", $content, $titleMatch, PREG_OFFSET_CAPTURE, $headTagIndex);
		// if found
		if ($hasTitle > 0)
			$startHeadIndex = $titleMatch[0][1] + strlen($titleMatch[0][0]);
		else
		{
			$hasHead = preg_match("%<head[^>]*>\s*%", $content, $headMatch, PREG_OFFSET_CAPTURE, $headTagIndex);
			$startHeadIndex = $headMatch[0][1] + strlen($headMatch[0][0]);
		}
	
		// start of the page up to the beginning of the editable part of head
		$startHead = substr($content, 0, $startHeadIndex);
		// existing head content from start of editable content to end of head section
		// this variable will contain the edited head content once processing complete
		$editedHead = substr($content, $startHeadIndex, $endHeadTagIndex - $startHeadIndex);
		// the rest of the page
		$body = substr($content, $endHeadTagIndex);
	
		//$this->printTimedProgress(microtime(true) - $startTime, "HTML Content Extract", $body);
		//$progTime = microtime(true);
		
		// remove all jquery copies from this page if appropriate
		if ($this->removeJQuery)
		{
			$ret = preg_replace(self::$jQueryRegex, '', $editedHead);
			if ($ret != NULL)
				$editedHead =& $ret;
		}
		
		//$this->printTimedProgress(microtime(true) - $progTime, "Process javascript libraries", $body);
		//$progTime = microtime(true);
		
		/*****************
		
			Handle Group Actions
			
		*****************/
		
		// handle action groups
		$resourceMatches = array();
		
		// array of match offsets and lengths to extract from the head what needs to be kept
		$matchOffsetPairs = array();
		$matchOffsets = array();
		$matchCount = 0;
		
		/****
		
			Extract Group Matches
			
		****/
		foreach ($this->resources as $group => &$groupParams)
		{
			// if there are no resources listed in this group
			if (!isset($groupParams['resources']))
				continue;
			
			$grpAssign = $groupParams['assignment'];
			$grpHasSelected = isset($groupParams['menuitems']);
			$menuItemSelected = $grpHasSelected && $this->item_id >= 0 ? isset($groupParams['menuitems'][$this->item_id]) : false;
			
			if ($grpAssign === 0 && !$menuItemSelected)
				continue;
			
			if ($grpAssign === 1 && $menuItemSelected)
				continue;
			
			// for each resource in this group
			foreach ($groupParams['resources'] as $index => $res)
			{
				switch($res['type'])
				{
					case 0:
						$isMatch = preg_match(self::$scriptRegexStart . preg_quote($res['data'], '%') . self::$scriptRegexEnd, $editedHead, $matches, PREG_OFFSET_CAPTURE);
						if ($isMatch > 0)
						{
							$match = $matches[0][0];
							$offset = $matches[0][1];
						}
						break;
					case 1:
						$isMatch = preg_match(self::$linkRegexStart . preg_quote($res['data'], '%') . self::$linkRegexEnd, $editedHead, $matches, PREG_OFFSET_CAPTURE);
						if ($isMatch > 0)
						{
							$match = $matches[0][0];
							$offset = $matches[0][1];
						}
						break;
					case 2:
						$numMatches = preg_match_all(self::$styleRegex, $editedHead, $matches, PREG_OFFSET_CAPTURE);
						// now search for the identifiable string in any of the matches
						$isMatch = 0;
						for ($i = 0; $i < $numMatches; $i++)
						{
							if (strpos($matches[0][$i][0], $res['data']) !== false)
							{
								$isMatch = 1;
								$match = $matches[0][$i][0];
								$offset = $matches[0][$i][1];
								break;
							}
						}
						break;
					case 3:
						$numMatches = preg_match_all(self::$customScriptRegex, $editedHead, $matches, PREG_OFFSET_CAPTURE);
						// now search for the identifiable string in any of the matches
						$isMatch = 0;
						for ($i = 0; $i < $numMatches; $i++)
						{
							if (strpos($matches[0][$i][0], $res['data']) !== false)
							{
								$isMatch = 1;
								$match = $matches[0][$i][0];
								$offset = $matches[0][$i][1];
								break;
							}
						}
						break;
					case 4:
						$isMatch = preg_match('%'.preg_quote($res['data'],'%').'\s*%', $editedHead, $matches, PREG_OFFSET_CAPTURE);
						if ($isMatch > 0)
						{
							$match = $matches[0][0];
							$offset = $matches[0][1];
						}
						break;
					case 5:
						$isMatch = preg_match($res['data'], $editedHead, $matches, PREG_OFFSET_CAPTURE);
						if ($isMatch > 0)
						{
							$match = $matches[0][0];
							$offset = $matches[0][1];
						}
						break;
					default:
						$isMatch = 0;
						break;
				}
				// if found add to matches data structure
				if ($isMatch > 0)
				{
					$resourceMatches[$group]['resources'][] = array( 'match' => $match, 'offset' => $offset);
					// also add offset's and length for easy extracting of unmatched content
					$matchOffsetPairs[] = array('offset' => $offset, 'length' => strlen($match));
					// and just store offsets in another array so the matchOffsetPairs can be sorted by offset
					$matchOffsets[] = $offset;
					$matchCount++;
					// we only need to add some things for each group once, if any match is found, so do this only if this is the first match for the group
					if (!isset($resourceMatches[$group]['position']))
					{
						$resourceMatches[$group]['position'] = $groupParams['position'];
						$resourceMatches[$group]['action'] = $groupParams['action'];
					}
				}
			}
		}
		
		//$this->printTimedProgress(microtime(true) - $progTime, "Found Group Matches", $body);
		//$progTime = microtime(true);
		
		/*****
		
			Process Group Matches
			
		******/
		
		// only proceed if any matches found
		if ($matchCount > 0)
		{
			// in this section $editedHead retains the content in the state it was prior to processing these groups
			// while newHead builds the new head content that will replace it after this stage
		
			// new sorted head content after applying group actions
			$newHead = '';
			// sort the recorded offsets and length for easy extracting of head data to keep that's unordered
			array_multisort($matchOffsets, SORT_ASC, $matchOffsetPairs);
			
			// check for overlaps
			if ($this->checkForOverlaps)
			{
				$matchEnd = $matchOffsetPairs[0]['offset'] + $matchOffsetPairs[0]['length'];
				for ($i = 1; $i < $matchCount; $i++)
				{
					$matchOffset = $matchOffsetPairs[$i]['offset'];
					// we have overlap!!!
					if ($matchEnd > $matchOffset)
					{
						// find start of body content
						preg_match('%<body[^>]*>%', $content, $bodyMatch, PREG_OFFSET_CAPTURE);
						$startBodyContent = $bodyMatch[0][1] + strlen($bodyMatch[0][0]);
						
						// add debug info to the start of the html body
						$this->prependHTML('<p id="interext-debug" style="display:none;">Match starting at offset: ' . 
							$matchOffsetPairs[$i-1]['offset'] . 
							' [ ' . 
							htmlspecialchars(substr($editedHead, $matchOffsetPairs[$i-1]['offset'], $matchOffsetPairs[$i-1]['length'])) .
							' ], overlaps with match starting at offset: ' . 
							$matchOffset . 
							' [ ' . 
							htmlspecialchars(substr($editedHead, $matchOffset, $matchOffsetPairs[$i]['length'])) . 
							' ] </p>');
						
						return true;
					}
					$matchEnd = $matchOffset + $matchOffsetPairs[$i]['length'];
				}
			}
			
			// add the top
			foreach ($resourceMatches as $group => &$groupMatches)
			{
				// group position top
				if ($groupMatches['position'] != 0)
					continue;	
				
				switch ($groupMatches['action'])
				{
					// sort, so output all
					case 0:
						// each match in this group
						foreach ($groupMatches['resources'] as $index => &$resourceMatch)
						{
							$resMatch = $resourceMatch['match'];
							$newHead .= $resMatch;
						}
						break;
					// unique, so output first
					case 1:
						$newHead .= $groupMatches['resources'][0]['match'];
						break;
				}
			}
			
			// add all unordered content (everything between matches)
			$currIndex = 0;
			foreach ($matchOffsetPairs as $i => $matchOffsetPair)
			{
				$offset = $matchOffsetPair['offset'];
				$length = $matchOffsetPair['length'];
				$newHead .= substr($editedHead, $currIndex, $offset - $currIndex);
				$currIndex = $offset + $length;
			}
			// add the rest after last match
			$newHead .= substr($editedHead, $currIndex);
			
			// add the bottom
			foreach ($resourceMatches as $group => &$groupMatches)
			{
				// if group position is bottom
				if ($groupMatches['position'] != 1)
					continue;	
				
				switch ($groupMatches['action'])
				{
					// sort, so output all
					case 0:
						// each match in this group
						foreach ($groupMatches['resources'] as $index => &$resourceMatch)
						{
							$resMatch = $resourceMatch['match'];
							$newHead .= $resMatch;
						}
						break;
					// unique, so output first
					case 1:
						$newHead .= $groupMatches['resources'][0]['match'];
						break;
				}
			}
			
			// point the editable portion of the head to the new head
			$editedHead =& $newHead;
		}
		
		//$this->printTimedProgress(microtime(true) - $progTime, "Process Group Actions", $body);
		//$progTime = microtime(true);
		
		/**************
		
			Add required system libraries to the start.
			
		**************/
		
		$libraryCode = '';
		
		if ($this->addMootools)
		{
			$libraryCode .= "<script src=\"{$this->mootoolsURL}\" type=\"text/javascript\"></script>\n  ";
		}
		
		if ($this->addMootoolsMore)
		{
			$libraryCode .= "<script src=\"{$this->mootoolsMoreURL}\" type=\"text/javascript\"></script>\n  ";
		}
		
		if ($this->addJQuery)
		{
			$libraryCode .= "<script src=\"{$this->jQueryURL}\" type=\"text/javascript\"></script>\n  ";
			if ($this->jQueryNoConflict)
			{
				$libraryCode .= "<script type=\"text/javascript\">jQuery.noConflict();</script>\n  ";
			}
		}
		
		$editedHead = "{$libraryCode}{$editedHead}";
		
		//$this->printTimedProgress(microtime(true) - $progTime, "Process Library Code Additions", $body);
		//$progTime = microtime(true);
		
		/*************
		
			Update the rendered page content
			
		*************/
		
		$content = "{$startHead}{$editedHead}{$body}";
		JResponse::setBody($content);
		
		/************
		
			Output performance info if asked to.
			
		************/
		
		if ($this->displayTime)
		{
			$endTime = microtime(true);
			$this->onAfterRenderTime = $endTime - $startTime;
			$this->prependHTML('<p id="interext-time" style="display:none;">Init Time: ' . $this->initTime . ', onBeforeRender Time: ' . $this->onBeforeRenderTime . ', onAfterRender Time: ' . $this->onAfterRenderTime . ', Total Time: ' . $this->totalTime . '</p>');
		}
		
		return true;
	}
	
	function prependHTML($html)
	{
		$content = JResponse::getBody();
		// find start of body content
		preg_match('%<body[^>]*>%', $content, $bodyMatch, PREG_OFFSET_CAPTURE);
		$startBodyContent = $bodyMatch[0][1] + strlen($bodyMatch[0][0]);
		
		// add debug info to the start of the html body
		$content = substr($content, 0, $startBodyContent) . 
			$html . 
			substr($content, $startBodyContent);
			
		// set the new body
		JResponse::setBody($content);
	}
	
	function printTimedProgress($progTime, $phase, &$body)
	{
		$body = "<p style=\"display:none;\">After phase {$phase}: Time - {$progTime}</p>\n{$body}";
	}
}
