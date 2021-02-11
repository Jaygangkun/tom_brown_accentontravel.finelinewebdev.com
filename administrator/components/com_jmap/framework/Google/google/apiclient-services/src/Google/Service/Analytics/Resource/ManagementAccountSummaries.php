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
 * The "accountSummaries" collection of methods.
 * Typical usage is:
 *  <code>
 *   $analyticsService = new Google_Service_Analytics(...);
 *   $accountSummaries = $analyticsService->accountSummaries;
 *  </code>
 */
class Google_Service_Analytics_Resource_ManagementAccountSummaries extends Google_Service_Resource
{
  /**
   * Lists account summaries (lightweight tree comprised of
   * accounts/properties/profiles) to which the user has access.
   * (accountSummaries.listManagementAccountSummaries)
   *
   * @param array $optParams Optional parameters.
   *
   * @opt_param int start-index An index of the first entity to retrieve. Use this
   * parameter as a pagination mechanism along with the max-results parameter.
   * @opt_param int max-results The maximum number of account summaries to include
   * in this response, where the largest acceptable value is 1000.
   * @return Google_Service_Analytics_AccountSummaries
   */
  public function listManagementAccountSummaries($optParams = array())
  {
    $params = array();
    $params = array_merge($params, $optParams);
    return $this->call('list', array($params), "Google_Service_Analytics_AccountSummaries");
  }
}
