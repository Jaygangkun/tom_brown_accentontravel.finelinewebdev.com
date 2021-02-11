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
 * The "accounts" collection of methods.
 * Typical usage is:
 *  <code>
 *   $analyticsService = new Google_Service_Analytics(...);
 *   $accounts = $analyticsService->accounts;
 *  </code>
 */
class Google_Service_Analytics_Resource_ManagementAccounts extends Google_Service_Resource
{
  /**
   * Lists all accounts to which the user has access.
   * (accounts.listManagementAccounts)
   *
   * @param array $optParams Optional parameters.
   *
   * @opt_param int max-results The maximum number of accounts to include in this
   * response.
   * @opt_param int start-index An index of the first account to retrieve. Use
   * this parameter as a pagination mechanism along with the max-results
   * parameter.
   * @return Google_Service_Analytics_Accounts
   */
  public function listManagementAccounts($optParams = array())
  {
    $params = array();
    $params = array_merge($params, $optParams);
    return $this->call('list', array($params), "Google_Service_Analytics_Accounts");
  }
}
