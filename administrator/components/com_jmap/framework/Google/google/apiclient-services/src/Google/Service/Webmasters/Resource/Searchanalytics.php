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
 * The "searchanalytics" collection of methods.
 * Typical usage is:
 *  <code>
 *   $webmastersService = new Google_Service_Webmasters(...);
 *   $searchanalytics = $webmastersService->searchanalytics;
 *  </code>
 */
class Google_Service_Webmasters_Resource_Searchanalytics extends Google_Service_Resource
{
  /**
   * Query your data with filters and parameters that you define. Returns zero or
   * more rows grouped by the row keys that you define. You must define a date
   * range of one or more days.
   *
   * When date is one of the group by values, any days without data are omitted
   * from the result list. If you need to know which days have data, issue a broad
   * date range query grouped by date for any metric, and see which day rows are
   * returned. (searchanalytics.query)
   *
   * @param string $siteUrl The site's URL, including protocol. For example:
   * http://www.example.com/
   * @param Google_Service_Webmasters_SearchAnalyticsQueryRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Google_Service_Webmasters_SearchAnalyticsQueryResponse
   */
  public function query($siteUrl, Google_Service_Webmasters_SearchAnalyticsQueryRequest $postBody, $optParams = array())
  {
    $params = array('siteUrl' => $siteUrl, 'postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('query', array($params), "Google_Service_Webmasters_SearchAnalyticsQueryResponse");
  }
}
