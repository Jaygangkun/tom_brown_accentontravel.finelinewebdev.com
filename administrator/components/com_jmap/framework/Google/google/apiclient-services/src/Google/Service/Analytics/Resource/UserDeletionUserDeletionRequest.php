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
 * The "userDeletionRequest" collection of methods.
 * Typical usage is:
 *  <code>
 *   $analyticsService = new Google_Service_Analytics(...);
 *   $userDeletionRequest = $analyticsService->userDeletionRequest;
 *  </code>
 */
class Google_Service_Analytics_Resource_UserDeletionUserDeletionRequest extends Google_Service_Resource
{
  /**
   * Insert or update a user deletion requests. (userDeletionRequest.upsert)
   *
   * @param Google_Service_Analytics_UserDeletionRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Google_Service_Analytics_UserDeletionRequest
   */
  public function upsert(Google_Service_Analytics_UserDeletionRequest $postBody, $optParams = array())
  {
    $params = array('postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('upsert', array($params), "Google_Service_Analytics_UserDeletionRequest");
  }
}
