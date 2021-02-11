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

class Google_Service_AnalyticsData_PropertyQuota extends Google_Model
{
  protected $concurrentRequestsType = 'Google_Service_AnalyticsData_QuotaStatus';
  protected $concurrentRequestsDataType = '';
  protected $serverErrorsPerProjectPerHourType = 'Google_Service_AnalyticsData_QuotaStatus';
  protected $serverErrorsPerProjectPerHourDataType = '';
  protected $tokensPerDayType = 'Google_Service_AnalyticsData_QuotaStatus';
  protected $tokensPerDayDataType = '';
  protected $tokensPerHourType = 'Google_Service_AnalyticsData_QuotaStatus';
  protected $tokensPerHourDataType = '';

  /**
   * @param Google_Service_AnalyticsData_QuotaStatus
   */
  public function setConcurrentRequests(Google_Service_AnalyticsData_QuotaStatus $concurrentRequests)
  {
    $this->concurrentRequests = $concurrentRequests;
  }
  /**
   * @return Google_Service_AnalyticsData_QuotaStatus
   */
  public function getConcurrentRequests()
  {
    return $this->concurrentRequests;
  }
  /**
   * @param Google_Service_AnalyticsData_QuotaStatus
   */
  public function setServerErrorsPerProjectPerHour(Google_Service_AnalyticsData_QuotaStatus $serverErrorsPerProjectPerHour)
  {
    $this->serverErrorsPerProjectPerHour = $serverErrorsPerProjectPerHour;
  }
  /**
   * @return Google_Service_AnalyticsData_QuotaStatus
   */
  public function getServerErrorsPerProjectPerHour()
  {
    return $this->serverErrorsPerProjectPerHour;
  }
  /**
   * @param Google_Service_AnalyticsData_QuotaStatus
   */
  public function setTokensPerDay(Google_Service_AnalyticsData_QuotaStatus $tokensPerDay)
  {
    $this->tokensPerDay = $tokensPerDay;
  }
  /**
   * @return Google_Service_AnalyticsData_QuotaStatus
   */
  public function getTokensPerDay()
  {
    return $this->tokensPerDay;
  }
  /**
   * @param Google_Service_AnalyticsData_QuotaStatus
   */
  public function setTokensPerHour(Google_Service_AnalyticsData_QuotaStatus $tokensPerHour)
  {
    $this->tokensPerHour = $tokensPerHour;
  }
  /**
   * @return Google_Service_AnalyticsData_QuotaStatus
   */
  public function getTokensPerHour()
  {
    return $this->tokensPerHour;
  }
}
