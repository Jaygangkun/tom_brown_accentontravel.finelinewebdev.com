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

class Google_Service_AnalyticsData_BatchRunReportsResponse extends Google_Collection
{
  protected $collection_key = 'reports';
  protected $reportsType = 'Google_Service_AnalyticsData_RunReportResponse';
  protected $reportsDataType = 'array';

  /**
   * @param Google_Service_AnalyticsData_RunReportResponse
   */
  public function setReports($reports)
  {
    $this->reports = $reports;
  }
  /**
   * @return Google_Service_AnalyticsData_RunReportResponse
   */
  public function getReports()
  {
    return $this->reports;
  }
}
