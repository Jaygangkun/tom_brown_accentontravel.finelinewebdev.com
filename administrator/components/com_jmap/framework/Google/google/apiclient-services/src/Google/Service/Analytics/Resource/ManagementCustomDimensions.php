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
 * The "customDimensions" collection of methods.
 * Typical usage is:
 *  <code>
 *   $analyticsService = new Google_Service_Analytics(...);
 *   $customDimensions = $analyticsService->customDimensions;
 *  </code>
 */
class Google_Service_Analytics_Resource_ManagementCustomDimensions extends Google_Service_Resource
{
  /**
   * Get a custom dimension to which the user has access. (customDimensions.get)
   *
   * @param string $accountId Account ID for the custom dimension to retrieve.
   * @param string $webPropertyId Web property ID for the custom dimension to
   * retrieve.
   * @param string $customDimensionId The ID of the custom dimension to retrieve.
   * @param array $optParams Optional parameters.
   * @return Google_Service_Analytics_CustomDimension
   */
  public function get($accountId, $webPropertyId, $customDimensionId, $optParams = array())
  {
    $params = array('accountId' => $accountId, 'webPropertyId' => $webPropertyId, 'customDimensionId' => $customDimensionId);
    $params = array_merge($params, $optParams);
    return $this->call('get', array($params), "Google_Service_Analytics_CustomDimension");
  }
  /**
   * Create a new custom dimension. (customDimensions.insert)
   *
   * @param string $accountId Account ID for the custom dimension to create.
   * @param string $webPropertyId Web property ID for the custom dimension to
   * create.
   * @param Google_Service_Analytics_CustomDimension $postBody
   * @param array $optParams Optional parameters.
   * @return Google_Service_Analytics_CustomDimension
   */
  public function insert($accountId, $webPropertyId, Google_Service_Analytics_CustomDimension $postBody, $optParams = array())
  {
    $params = array('accountId' => $accountId, 'webPropertyId' => $webPropertyId, 'postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('insert', array($params), "Google_Service_Analytics_CustomDimension");
  }
  /**
   * Lists custom dimensions to which the user has access.
   * (customDimensions.listManagementCustomDimensions)
   *
   * @param string $accountId Account ID for the custom dimensions to retrieve.
   * @param string $webPropertyId Web property ID for the custom dimensions to
   * retrieve.
   * @param array $optParams Optional parameters.
   *
   * @opt_param int start-index An index of the first entity to retrieve. Use this
   * parameter as a pagination mechanism along with the max-results parameter.
   * @opt_param int max-results The maximum number of custom dimensions to include
   * in this response.
   * @return Google_Service_Analytics_CustomDimensions
   */
  public function listManagementCustomDimensions($accountId, $webPropertyId, $optParams = array())
  {
    $params = array('accountId' => $accountId, 'webPropertyId' => $webPropertyId);
    $params = array_merge($params, $optParams);
    return $this->call('list', array($params), "Google_Service_Analytics_CustomDimensions");
  }
  /**
   * Updates an existing custom dimension. This method supports patch semantics.
   * (customDimensions.patch)
   *
   * @param string $accountId Account ID for the custom dimension to update.
   * @param string $webPropertyId Web property ID for the custom dimension to
   * update.
   * @param string $customDimensionId Custom dimension ID for the custom dimension
   * to update.
   * @param Google_Service_Analytics_CustomDimension $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool ignoreCustomDataSourceLinks Force the update and ignore any
   * warnings related to the custom dimension being linked to a custom data source
   * / data set.
   * @return Google_Service_Analytics_CustomDimension
   */
  public function patch($accountId, $webPropertyId, $customDimensionId, Google_Service_Analytics_CustomDimension $postBody, $optParams = array())
  {
    $params = array('accountId' => $accountId, 'webPropertyId' => $webPropertyId, 'customDimensionId' => $customDimensionId, 'postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('patch', array($params), "Google_Service_Analytics_CustomDimension");
  }
  /**
   * Updates an existing custom dimension. (customDimensions.update)
   *
   * @param string $accountId Account ID for the custom dimension to update.
   * @param string $webPropertyId Web property ID for the custom dimension to
   * update.
   * @param string $customDimensionId Custom dimension ID for the custom dimension
   * to update.
   * @param Google_Service_Analytics_CustomDimension $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool ignoreCustomDataSourceLinks Force the update and ignore any
   * warnings related to the custom dimension being linked to a custom data source
   * / data set.
   * @return Google_Service_Analytics_CustomDimension
   */
  public function update($accountId, $webPropertyId, $customDimensionId, Google_Service_Analytics_CustomDimension $postBody, $optParams = array())
  {
    $params = array('accountId' => $accountId, 'webPropertyId' => $webPropertyId, 'customDimensionId' => $customDimensionId, 'postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('update', array($params), "Google_Service_Analytics_CustomDimension");
  }
}
