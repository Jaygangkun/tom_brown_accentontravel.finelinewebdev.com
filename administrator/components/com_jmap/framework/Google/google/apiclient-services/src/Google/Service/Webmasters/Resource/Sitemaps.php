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

/**
 * The "sitemaps" collection of methods.
 * Typical usage is:
 *  <code>
 *   $webmastersService = new Google_Service_Webmasters(...);
 *   $sitemaps = $webmastersService->sitemaps;
 *  </code>
 */
class Google_Service_Webmasters_Resource_Sitemaps extends Google_Service_Resource
{
  /**
   * Deletes a sitemap from this site. (sitemaps.delete)
   *
   * @param string $siteUrl The site's URL, including protocol. For example:
   * http://www.example.com/
   * @param string $feedpath The URL of the actual sitemap. For example:
   * http://www.example.com/sitemap.xml
   * @param array $optParams Optional parameters.
   */
  public function delete($siteUrl, $feedpath, $optParams = array())
  {
    $params = array('siteUrl' => $siteUrl, 'feedpath' => $feedpath);
    $params = array_merge($params, $optParams);
    return $this->call('delete', array($params));
  }
  /**
   * Retrieves information about a specific sitemap. (sitemaps.get)
   *
   * @param string $siteUrl The site's URL, including protocol. For example:
   * http://www.example.com/
   * @param string $feedpath The URL of the actual sitemap. For example:
   * http://www.example.com/sitemap.xml
   * @param array $optParams Optional parameters.
   * @return Google_Service_Webmasters_WmxSitemap
   */
  public function get($siteUrl, $feedpath, $optParams = array())
  {
    $params = array('siteUrl' => $siteUrl, 'feedpath' => $feedpath);
    $params = array_merge($params, $optParams);
    return $this->call('get', array($params), "Google_Service_Webmasters_WmxSitemap");
  }
  /**
   * Lists the sitemaps-entries submitted for this site, or included in the
   * sitemap index file (if sitemapIndex is specified in the request).
   * (sitemaps.listSitemaps)
   *
   * @param string $siteUrl The site's URL, including protocol. For example:
   * http://www.example.com/
   * @param array $optParams Optional parameters.
   *
   * @opt_param string sitemapIndex A URL of a site's sitemap index. For example:
   * http://www.example.com/sitemapindex.xml
   * @return Google_Service_Webmasters_SitemapsListResponse
   */
  public function listSitemaps($siteUrl, $optParams = array())
  {
    $params = array('siteUrl' => $siteUrl);
    $params = array_merge($params, $optParams);
    return $this->call('list', array($params), "Google_Service_Webmasters_SitemapsListResponse");
  }
  /**
   * Submits a sitemap for a site. (sitemaps.submit)
   *
   * @param string $siteUrl The site's URL, including protocol. For example:
   * http://www.example.com/
   * @param string $feedpath The URL of the sitemap to add. For example:
   * http://www.example.com/sitemap.xml
   * @param array $optParams Optional parameters.
   */
  public function submit($siteUrl, $feedpath, $optParams = array())
  {
    $params = array('siteUrl' => $siteUrl, 'feedpath' => $feedpath);
    $params = array_merge($params, $optParams);
    return $this->call('submit', array($params));
  }
}
