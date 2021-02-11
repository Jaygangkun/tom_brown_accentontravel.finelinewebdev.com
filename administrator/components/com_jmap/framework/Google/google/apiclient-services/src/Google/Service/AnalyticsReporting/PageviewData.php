<?php
// namespace administrator\components\com_jmap\framework\google;
/**
 *
 * @package JMAP::FRAMEWORK::administrator::components::com_jmap
 * @subpackage framework
 * @subpackage google
 * @author Joomla! Extensions Store
 * @copyright (C) 2015 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined ( '_JEXEC' ) or die ();

class Google_Service_AnalyticsReporting_PageviewData extends Google_Model
{
  public $pagePath;
  public $pageTitle;

  public function setPagePath($pagePath)
  {
    $this->pagePath = $pagePath;
  }
  public function getPagePath()
  {
    return $this->pagePath;
  }
  public function setPageTitle($pageTitle)
  {
    $this->pageTitle = $pageTitle;
  }
  public function getPageTitle()
  {
    return $this->pageTitle;
  }
}
