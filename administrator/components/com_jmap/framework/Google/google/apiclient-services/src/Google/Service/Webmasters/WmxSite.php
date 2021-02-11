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

class Google_Service_Webmasters_WmxSite extends Google_Model
{
  public $permissionLevel;
  public $siteUrl;

  public function setPermissionLevel($permissionLevel)
  {
    $this->permissionLevel = $permissionLevel;
  }
  public function getPermissionLevel()
  {
    return $this->permissionLevel;
  }
  public function setSiteUrl($siteUrl)
  {
    $this->siteUrl = $siteUrl;
  }
  public function getSiteUrl()
  {
    return $this->siteUrl;
  }
}
