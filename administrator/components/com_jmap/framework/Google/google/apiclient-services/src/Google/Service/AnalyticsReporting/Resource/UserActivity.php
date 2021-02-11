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
 * The "userActivity" collection of methods.
 * Typical usage is:
 *  <code>
 *   $analyticsreportingService = new Google_Service_AnalyticsReporting(...);
 *   $userActivity = $analyticsreportingService->userActivity;
 *  </code>
 */
class Google_Service_AnalyticsReporting_Resource_UserActivity extends Google_Service_Resource
{
  /**
   * Returns User Activity data. (userActivity.search)
   *
   * @param Google_Service_AnalyticsReporting_SearchUserActivityRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Google_Service_AnalyticsReporting_SearchUserActivityResponse
   */
  public function search(Google_Service_AnalyticsReporting_SearchUserActivityRequest $postBody, $optParams = array())
  {
    $params = array('postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('search', array($params), "Google_Service_AnalyticsReporting_SearchUserActivityResponse");
  }
}
