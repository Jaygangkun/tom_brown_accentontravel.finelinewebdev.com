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

class Google_Service_Webmasters_SitesListResponse extends Google_Collection
{
  protected $collection_key = 'siteEntry';
  protected $siteEntryType = 'Google_Service_Webmasters_WmxSite';
  protected $siteEntryDataType = 'array';

  /**
   * @param Google_Service_Webmasters_WmxSite
   */
  public function setSiteEntry($siteEntry)
  {
    $this->siteEntry = $siteEntry;
  }
  /**
   * @return Google_Service_Webmasters_WmxSite
   */
  public function getSiteEntry()
  {
    return $this->siteEntry;
  }
}
