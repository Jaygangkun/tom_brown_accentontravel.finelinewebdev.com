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

class Google_Service_AnalyticsData_CohortSpec extends Google_Collection
{
  protected $collection_key = 'cohorts';
  protected $cohortReportSettingsType = 'Google_Service_AnalyticsData_CohortReportSettings';
  protected $cohortReportSettingsDataType = '';
  protected $cohortsType = 'Google_Service_AnalyticsData_Cohort';
  protected $cohortsDataType = 'array';
  protected $cohortsRangeType = 'Google_Service_AnalyticsData_CohortsRange';
  protected $cohortsRangeDataType = '';

  /**
   * @param Google_Service_AnalyticsData_CohortReportSettings
   */
  public function setCohortReportSettings(Google_Service_AnalyticsData_CohortReportSettings $cohortReportSettings)
  {
    $this->cohortReportSettings = $cohortReportSettings;
  }
  /**
   * @return Google_Service_AnalyticsData_CohortReportSettings
   */
  public function getCohortReportSettings()
  {
    return $this->cohortReportSettings;
  }
  /**
   * @param Google_Service_AnalyticsData_Cohort
   */
  public function setCohorts($cohorts)
  {
    $this->cohorts = $cohorts;
  }
  /**
   * @return Google_Service_AnalyticsData_Cohort
   */
  public function getCohorts()
  {
    return $this->cohorts;
  }
  /**
   * @param Google_Service_AnalyticsData_CohortsRange
   */
  public function setCohortsRange(Google_Service_AnalyticsData_CohortsRange $cohortsRange)
  {
    $this->cohortsRange = $cohortsRange;
  }
  /**
   * @return Google_Service_AnalyticsData_CohortsRange
   */
  public function getCohortsRange()
  {
    return $this->cohortsRange;
  }
}
