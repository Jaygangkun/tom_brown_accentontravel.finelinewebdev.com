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

class Google_Service_Webmasters_SitemapsListResponse extends Google_Collection
{
  protected $collection_key = 'sitemap';
  protected $sitemapType = 'Google_Service_Webmasters_WmxSitemap';
  protected $sitemapDataType = 'array';

  /**
   * @param Google_Service_Webmasters_WmxSitemap
   */
  public function setSitemap($sitemap)
  {
    $this->sitemap = $sitemap;
  }
  /**
   * @return Google_Service_Webmasters_WmxSitemap
   */
  public function getSitemap()
  {
    return $this->sitemap;
  }
}
