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
 * The "profileFilterLinks" collection of methods.
 * Typical usage is:
 *  <code>
 *   $analyticsService = new Google_Service_Analytics(...);
 *   $profileFilterLinks = $analyticsService->profileFilterLinks;
 *  </code>
 */
class Google_Service_Analytics_Resource_ManagementProfileFilterLinks extends Google_Service_Resource
{
  /**
   * Delete a profile filter link. (profileFilterLinks.delete)
   *
   * @param string $accountId Account ID to which the profile filter link belongs.
   * @param string $webPropertyId Web property Id to which the profile filter link
   * belongs.
   * @param string $profileId Profile ID to which the filter link belongs.
   * @param string $linkId ID of the profile filter link to delete.
   * @param array $optParams Optional parameters.
   */
  public function delete($accountId, $webPropertyId, $profileId, $linkId, $optParams = array())
  {
    $params = array('accountId' => $accountId, 'webPropertyId' => $webPropertyId, 'profileId' => $profileId, 'linkId' => $linkId);
    $params = array_merge($params, $optParams);
    return $this->call('delete', array($params));
  }
  /**
   * Returns a single profile filter link. (profileFilterLinks.get)
   *
   * @param string $accountId Account ID to retrieve profile filter link for.
   * @param string $webPropertyId Web property Id to retrieve profile filter link
   * for.
   * @param string $profileId Profile ID to retrieve filter link for.
   * @param string $linkId ID of the profile filter link.
   * @param array $optParams Optional parameters.
   * @return Google_Service_Analytics_ProfileFilterLink
   */
  public function get($accountId, $webPropertyId, $profileId, $linkId, $optParams = array())
  {
    $params = array('accountId' => $accountId, 'webPropertyId' => $webPropertyId, 'profileId' => $profileId, 'linkId' => $linkId);
    $params = array_merge($params, $optParams);
    return $this->call('get', array($params), "Google_Service_Analytics_ProfileFilterLink");
  }
  /**
   * Create a new profile filter link. (profileFilterLinks.insert)
   *
   * @param string $accountId Account ID to create profile filter link for.
   * @param string $webPropertyId Web property Id to create profile filter link
   * for.
   * @param string $profileId Profile ID to create filter link for.
   * @param Google_Service_Analytics_ProfileFilterLink $postBody
   * @param array $optParams Optional parameters.
   * @return Google_Service_Analytics_ProfileFilterLink
   */
  public function insert($accountId, $webPropertyId, $profileId, Google_Service_Analytics_ProfileFilterLink $postBody, $optParams = array())
  {
    $params = array('accountId' => $accountId, 'webPropertyId' => $webPropertyId, 'profileId' => $profileId, 'postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('insert', array($params), "Google_Service_Analytics_ProfileFilterLink");
  }
  /**
   * Lists all profile filter links for a profile.
   * (profileFilterLinks.listManagementProfileFilterLinks)
   *
   * @param string $accountId Account ID to retrieve profile filter links for.
   * @param string $webPropertyId Web property Id for profile filter links for.
   * Can either be a specific web property ID or '~all', which refers to all the
   * web properties that user has access to.
   * @param string $profileId Profile ID to retrieve filter links for. Can either
   * be a specific profile ID or '~all', which refers to all the profiles that
   * user has access to.
   * @param array $optParams Optional parameters.
   *
   * @opt_param int max-results The maximum number of profile filter links to
   * include in this response.
   * @opt_param int start-index An index of the first entity to retrieve. Use this
   * parameter as a pagination mechanism along with the max-results parameter.
   * @return Google_Service_Analytics_ProfileFilterLinks
   */
  public function listManagementProfileFilterLinks($accountId, $webPropertyId, $profileId, $optParams = array())
  {
    $params = array('accountId' => $accountId, 'webPropertyId' => $webPropertyId, 'profileId' => $profileId);
    $params = array_merge($params, $optParams);
    return $this->call('list', array($params), "Google_Service_Analytics_ProfileFilterLinks");
  }
  /**
   * Update an existing profile filter link. This method supports patch semantics.
   * (profileFilterLinks.patch)
   *
   * @param string $accountId Account ID to which profile filter link belongs.
   * @param string $webPropertyId Web property Id to which profile filter link
   * belongs
   * @param string $profileId Profile ID to which filter link belongs
   * @param string $linkId ID of the profile filter link to be updated.
   * @param Google_Service_Analytics_ProfileFilterLink $postBody
   * @param array $optParams Optional parameters.
   * @return Google_Service_Analytics_ProfileFilterLink
   */
  public function patch($accountId, $webPropertyId, $profileId, $linkId, Google_Service_Analytics_ProfileFilterLink $postBody, $optParams = array())
  {
    $params = array('accountId' => $accountId, 'webPropertyId' => $webPropertyId, 'profileId' => $profileId, 'linkId' => $linkId, 'postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('patch', array($params), "Google_Service_Analytics_ProfileFilterLink");
  }
  /**
   * Update an existing profile filter link. (profileFilterLinks.update)
   *
   * @param string $accountId Account ID to which profile filter link belongs.
   * @param string $webPropertyId Web property Id to which profile filter link
   * belongs
   * @param string $profileId Profile ID to which filter link belongs
   * @param string $linkId ID of the profile filter link to be updated.
   * @param Google_Service_Analytics_ProfileFilterLink $postBody
   * @param array $optParams Optional parameters.
   * @return Google_Service_Analytics_ProfileFilterLink
   */
  public function update($accountId, $webPropertyId, $profileId, $linkId, Google_Service_Analytics_ProfileFilterLink $postBody, $optParams = array())
  {
    $params = array('accountId' => $accountId, 'webPropertyId' => $webPropertyId, 'profileId' => $profileId, 'linkId' => $linkId, 'postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('update', array($params), "Google_Service_Analytics_ProfileFilterLink");
  }
}
