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
 * The "clientId" collection of methods.
 * Typical usage is:
 *  <code>
 *   $analyticsService = new Google_Service_Analytics(...);
 *   $clientId = $analyticsService->clientId;
 *  </code>
 */
class Google_Service_Analytics_Resource_ManagementClientId extends Google_Service_Resource
{
  /**
   * Hashes the given Client ID. (clientId.hashClientId)
   *
   * @param Google_Service_Analytics_HashClientIdRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Google_Service_Analytics_HashClientIdResponse
   */
  public function hashClientId(Google_Service_Analytics_HashClientIdRequest $postBody, $optParams = array())
  {
    $params = array('postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('hashClientId', array($params), "Google_Service_Analytics_HashClientIdResponse");
  }
}
