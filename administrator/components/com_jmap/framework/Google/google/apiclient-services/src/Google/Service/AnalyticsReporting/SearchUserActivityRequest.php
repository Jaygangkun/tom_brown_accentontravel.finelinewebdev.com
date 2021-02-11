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

class Google_Service_AnalyticsReporting_SearchUserActivityRequest extends Google_Collection
{
  protected $collection_key = 'activityTypes';
  public $activityTypes;
  protected $dateRangeType = 'Google_Service_AnalyticsReporting_DateRange';
  protected $dateRangeDataType = '';
  public $pageSize;
  public $pageToken;
  protected $userType = 'Google_Service_AnalyticsReporting_User';
  protected $userDataType = '';
  public $viewId;

  public function setActivityTypes($activityTypes)
  {
    $this->activityTypes = $activityTypes;
  }
  public function getActivityTypes()
  {
    return $this->activityTypes;
  }
  /**
   * @param Google_Service_AnalyticsReporting_DateRange
   */
  public function setDateRange(Google_Service_AnalyticsReporting_DateRange $dateRange)
  {
    $this->dateRange = $dateRange;
  }
  /**
   * @return Google_Service_AnalyticsReporting_DateRange
   */
  public function getDateRange()
  {
    return $this->dateRange;
  }
  public function setPageSize($pageSize)
  {
    $this->pageSize = $pageSize;
  }
  public function getPageSize()
  {
    return $this->pageSize;
  }
  public function setPageToken($pageToken)
  {
    $this->pageToken = $pageToken;
  }
  public function getPageToken()
  {
    return $this->pageToken;
  }
  /**
   * @param Google_Service_AnalyticsReporting_User
   */
  public function setUser(Google_Service_AnalyticsReporting_User $user)
  {
    $this->user = $user;
  }
  /**
   * @return Google_Service_AnalyticsReporting_User
   */
  public function getUser()
  {
    return $this->user;
  }
  public function setViewId($viewId)
  {
    $this->viewId = $viewId;
  }
  public function getViewId()
  {
    return $this->viewId;
  }
}
