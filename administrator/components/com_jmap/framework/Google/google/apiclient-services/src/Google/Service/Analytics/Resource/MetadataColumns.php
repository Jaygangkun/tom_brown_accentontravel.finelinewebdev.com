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
 * The "columns" collection of methods.
 * Typical usage is:
 *  <code>
 *   $analyticsService = new Google_Service_Analytics(...);
 *   $columns = $analyticsService->columns;
 *  </code>
 */
class Google_Service_Analytics_Resource_MetadataColumns extends Google_Service_Resource
{
  /**
   * Lists all columns for a report type (columns.listMetadataColumns)
   *
   * @param string $reportType Report type. Allowed Values: 'ga'. Where 'ga'
   * corresponds to the Core Reporting API
   * @param array $optParams Optional parameters.
   * @return Google_Service_Analytics_Columns
   */
  public function listMetadataColumns($reportType, $optParams = array())
  {
    $params = array('reportType' => $reportType);
    $params = array_merge($params, $optParams);
    return $this->call('list', array($params), "Google_Service_Analytics_Columns");
  }
}
