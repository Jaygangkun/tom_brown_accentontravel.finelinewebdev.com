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

class Google_Service_AnalyticsReporting_UserActivitySession extends Google_Collection
{
  protected $collection_key = 'activities';
  protected $activitiesType = 'Google_Service_AnalyticsReporting_Activity';
  protected $activitiesDataType = 'array';
  public $dataSource;
  public $deviceCategory;
  public $platform;
  public $sessionDate;
  public $sessionId;

  /**
   * @param Google_Service_AnalyticsReporting_Activity
   */
  public function setActivities($activities)
  {
    $this->activities = $activities;
  }
  /**
   * @return Google_Service_AnalyticsReporting_Activity
   */
  public function getActivities()
  {
    return $this->activities;
  }
  public function setDataSource($dataSource)
  {
    $this->dataSource = $dataSource;
  }
  public function getDataSource()
  {
    return $this->dataSource;
  }
  public function setDeviceCategory($deviceCategory)
  {
    $this->deviceCategory = $deviceCategory;
  }
  public function getDeviceCategory()
  {
    return $this->deviceCategory;
  }
  public function setPlatform($platform)
  {
    $this->platform = $platform;
  }
  public function getPlatform()
  {
    return $this->platform;
  }
  public function setSessionDate($sessionDate)
  {
    $this->sessionDate = $sessionDate;
  }
  public function getSessionDate()
  {
    return $this->sessionDate;
  }
  public function setSessionId($sessionId)
  {
    $this->sessionId = $sessionId;
  }
  public function getSessionId()
  {
    return $this->sessionId;
  }
}
