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
 * The "customDataSources" collection of methods.
 * Typical usage is:
 *  <code>
 *   $analyticsService = new Google_Service_Analytics(...);
 *   $customDataSources = $analyticsService->customDataSources;
 *  </code>
 */
class Google_Service_Analytics_Resource_ManagementCustomDataSources extends Google_Service_Resource
{
  /**
   * List custom data sources to which the user has access.
   * (customDataSources.listManagementCustomDataSources)
   *
   * @param string $accountId Account Id for the custom data sources to retrieve.
   * @param string $webPropertyId Web property Id for the custom data sources to
   * retrieve.
   * @param array $optParams Optional parameters.
   *
   * @opt_param int max-results The maximum number of custom data sources to
   * include in this response.
   * @opt_param int start-index A 1-based index of the first custom data source to
   * retrieve. Use this parameter as a pagination mechanism along with the max-
   * results parameter.
   * @return Google_Service_Analytics_CustomDataSources
   */
  public function listManagementCustomDataSources($accountId, $webPropertyId, $optParams = array())
  {
    $params = array('accountId' => $accountId, 'webPropertyId' => $webPropertyId);
    $params = array_merge($params, $optParams);
    return $this->call('list', array($params), "Google_Service_Analytics_CustomDataSources");
  }
}
