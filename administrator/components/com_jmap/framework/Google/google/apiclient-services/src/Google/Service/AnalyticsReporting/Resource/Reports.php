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
 * The "reports" collection of methods.
 * Typical usage is:
 *  <code>
 *   $analyticsreportingService = new Google_Service_AnalyticsReporting(...);
 *   $reports = $analyticsreportingService->reports;
 *  </code>
 */
class Google_Service_AnalyticsReporting_Resource_Reports extends Google_Service_Resource
{
  /**
   * Returns the Analytics data. (reports.batchGet)
   *
   * @param Google_Service_AnalyticsReporting_GetReportsRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Google_Service_AnalyticsReporting_GetReportsResponse
   */
  public function batchGet(Google_Service_AnalyticsReporting_GetReportsRequest $postBody, $optParams = array())
  {
    $params = array('postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('batchGet', array($params), "Google_Service_AnalyticsReporting_GetReportsResponse");
  }
}
