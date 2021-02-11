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

class Google_Service_AnalyticsData_BatchRunPivotReportsResponse extends Google_Collection
{
  protected $collection_key = 'pivotReports';
  protected $pivotReportsType = 'Google_Service_AnalyticsData_RunPivotReportResponse';
  protected $pivotReportsDataType = 'array';

  /**
   * @param Google_Service_AnalyticsData_RunPivotReportResponse
   */
  public function setPivotReports($pivotReports)
  {
    $this->pivotReports = $pivotReports;
  }
  /**
   * @return Google_Service_AnalyticsData_RunPivotReportResponse
   */
  public function getPivotReports()
  {
    return $this->pivotReports;
  }
}
