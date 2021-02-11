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

class Google_Service_AnalyticsReporting_GetReportsResponse extends Google_Collection
{
  protected $collection_key = 'reports';
  public $queryCost;
  protected $reportsType = 'Google_Service_AnalyticsReporting_Report';
  protected $reportsDataType = 'array';
  protected $resourceQuotasRemainingType = 'Google_Service_AnalyticsReporting_ResourceQuotasRemaining';
  protected $resourceQuotasRemainingDataType = '';

  public function setQueryCost($queryCost)
  {
    $this->queryCost = $queryCost;
  }
  public function getQueryCost()
  {
    return $this->queryCost;
  }
  /**
   * @param Google_Service_AnalyticsReporting_Report
   */
  public function setReports($reports)
  {
    $this->reports = $reports;
  }
  /**
   * @return Google_Service_AnalyticsReporting_Report
   */
  public function getReports()
  {
    return $this->reports;
  }
  /**
   * @param Google_Service_AnalyticsReporting_ResourceQuotasRemaining
   */
  public function setResourceQuotasRemaining(Google_Service_AnalyticsReporting_ResourceQuotasRemaining $resourceQuotasRemaining)
  {
    $this->resourceQuotasRemaining = $resourceQuotasRemaining;
  }
  /**
   * @return Google_Service_AnalyticsReporting_ResourceQuotasRemaining
   */
  public function getResourceQuotasRemaining()
  {
    return $this->resourceQuotasRemaining;
  }
}
