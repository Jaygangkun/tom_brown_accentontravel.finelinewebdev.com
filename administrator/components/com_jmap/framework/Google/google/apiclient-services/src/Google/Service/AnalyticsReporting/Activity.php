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

class Google_Service_AnalyticsReporting_Activity extends Google_Collection
{
  protected $collection_key = 'customDimension';
  public $activityTime;
  public $activityType;
  protected $appviewType = 'Google_Service_AnalyticsReporting_ScreenviewData';
  protected $appviewDataType = '';
  public $campaign;
  public $channelGrouping;
  protected $customDimensionType = 'Google_Service_AnalyticsReporting_CustomDimension';
  protected $customDimensionDataType = 'array';
  protected $ecommerceType = 'Google_Service_AnalyticsReporting_EcommerceData';
  protected $ecommerceDataType = '';
  protected $eventType = 'Google_Service_AnalyticsReporting_EventData';
  protected $eventDataType = '';
  protected $goalsType = 'Google_Service_AnalyticsReporting_GoalSetData';
  protected $goalsDataType = '';
  public $hostname;
  public $keyword;
  public $landingPagePath;
  public $medium;
  protected $pageviewType = 'Google_Service_AnalyticsReporting_PageviewData';
  protected $pageviewDataType = '';
  public $source;

  public function setActivityTime($activityTime)
  {
    $this->activityTime = $activityTime;
  }
  public function getActivityTime()
  {
    return $this->activityTime;
  }
  public function setActivityType($activityType)
  {
    $this->activityType = $activityType;
  }
  public function getActivityType()
  {
    return $this->activityType;
  }
  /**
   * @param Google_Service_AnalyticsReporting_ScreenviewData
   */
  public function setAppview(Google_Service_AnalyticsReporting_ScreenviewData $appview)
  {
    $this->appview = $appview;
  }
  /**
   * @return Google_Service_AnalyticsReporting_ScreenviewData
   */
  public function getAppview()
  {
    return $this->appview;
  }
  public function setCampaign($campaign)
  {
    $this->campaign = $campaign;
  }
  public function getCampaign()
  {
    return $this->campaign;
  }
  public function setChannelGrouping($channelGrouping)
  {
    $this->channelGrouping = $channelGrouping;
  }
  public function getChannelGrouping()
  {
    return $this->channelGrouping;
  }
  /**
   * @param Google_Service_AnalyticsReporting_CustomDimension
   */
  public function setCustomDimension($customDimension)
  {
    $this->customDimension = $customDimension;
  }
  /**
   * @return Google_Service_AnalyticsReporting_CustomDimension
   */
  public function getCustomDimension()
  {
    return $this->customDimension;
  }
  /**
   * @param Google_Service_AnalyticsReporting_EcommerceData
   */
  public function setEcommerce(Google_Service_AnalyticsReporting_EcommerceData $ecommerce)
  {
    $this->ecommerce = $ecommerce;
  }
  /**
   * @return Google_Service_AnalyticsReporting_EcommerceData
   */
  public function getEcommerce()
  {
    return $this->ecommerce;
  }
  /**
   * @param Google_Service_AnalyticsReporting_EventData
   */
  public function setEvent(Google_Service_AnalyticsReporting_EventData $event)
  {
    $this->event = $event;
  }
  /**
   * @return Google_Service_AnalyticsReporting_EventData
   */
  public function getEvent()
  {
    return $this->event;
  }
  /**
   * @param Google_Service_AnalyticsReporting_GoalSetData
   */
  public function setGoals(Google_Service_AnalyticsReporting_GoalSetData $goals)
  {
    $this->goals = $goals;
  }
  /**
   * @return Google_Service_AnalyticsReporting_GoalSetData
   */
  public function getGoals()
  {
    return $this->goals;
  }
  public function setHostname($hostname)
  {
    $this->hostname = $hostname;
  }
  public function getHostname()
  {
    return $this->hostname;
  }
  public function setKeyword($keyword)
  {
    $this->keyword = $keyword;
  }
  public function getKeyword()
  {
    return $this->keyword;
  }
  public function setLandingPagePath($landingPagePath)
  {
    $this->landingPagePath = $landingPagePath;
  }
  public function getLandingPagePath()
  {
    return $this->landingPagePath;
  }
  public function setMedium($medium)
  {
    $this->medium = $medium;
  }
  public function getMedium()
  {
    return $this->medium;
  }
  /**
   * @param Google_Service_AnalyticsReporting_PageviewData
   */
  public function setPageview(Google_Service_AnalyticsReporting_PageviewData $pageview)
  {
    $this->pageview = $pageview;
  }
  /**
   * @return Google_Service_AnalyticsReporting_PageviewData
   */
  public function getPageview()
  {
    return $this->pageview;
  }
  public function setSource($source)
  {
    $this->source = $source;
  }
  public function getSource()
  {
    return $this->source;
  }
}
