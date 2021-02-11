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
 * The "provisioning" collection of methods.
 * Typical usage is:
 *  <code>
 *   $analyticsService = new Google_Service_Analytics(...);
 *   $provisioning = $analyticsService->provisioning;
 *  </code>
 */
class Google_Service_Analytics_Resource_Provisioning extends Google_Service_Resource
{
  /**
   * Creates an account ticket. (provisioning.createAccountTicket)
   *
   * @param Google_Service_Analytics_AccountTicket $postBody
   * @param array $optParams Optional parameters.
   * @return Google_Service_Analytics_AccountTicket
   */
  public function createAccountTicket(Google_Service_Analytics_AccountTicket $postBody, $optParams = array())
  {
    $params = array('postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('createAccountTicket', array($params), "Google_Service_Analytics_AccountTicket");
  }
  /**
   * Provision account. (provisioning.createAccountTree)
   *
   * @param Google_Service_Analytics_AccountTreeRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Google_Service_Analytics_AccountTreeResponse
   */
  public function createAccountTree(Google_Service_Analytics_AccountTreeRequest $postBody, $optParams = array())
  {
    $params = array('postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('createAccountTree', array($params), "Google_Service_Analytics_AccountTreeResponse");
  }
}
