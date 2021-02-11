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

class Google_Service_AnalyticsReporting_GetReportsRequest extends Google_Collection
{
  protected $collection_key = 'reportRequests';
  protected $reportRequestsType = 'Google_Service_AnalyticsReporting_ReportRequest';
  protected $reportRequestsDataType = 'array';
  public $useResourceQuotas;

  /**
   * @param Google_Service_AnalyticsReporting_ReportRequest
   */
  public function setReportRequests($reportRequests)
  {
    $this->reportRequests = $reportRequests;
  }
  /**
   * @return Google_Service_AnalyticsReporting_ReportRequest
   */
  public function getReportRequests()
  {
    return $this->reportRequests;
  }
  public function setUseResourceQuotas($useResourceQuotas)
  {
    $this->useResourceQuotas = $useResourceQuotas;
  }
  public function getUseResourceQuotas()
  {
    return $this->useResourceQuotas;
  }
}
