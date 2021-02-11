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

class Google_Service_AnalyticsReporting_DynamicSegment extends Google_Model
{
  public $name;
  protected $sessionSegmentType = 'Google_Service_AnalyticsReporting_SegmentDefinition';
  protected $sessionSegmentDataType = '';
  protected $userSegmentType = 'Google_Service_AnalyticsReporting_SegmentDefinition';
  protected $userSegmentDataType = '';

  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
  /**
   * @param Google_Service_AnalyticsReporting_SegmentDefinition
   */
  public function setSessionSegment(Google_Service_AnalyticsReporting_SegmentDefinition $sessionSegment)
  {
    $this->sessionSegment = $sessionSegment;
  }
  /**
   * @return Google_Service_AnalyticsReporting_SegmentDefinition
   */
  public function getSessionSegment()
  {
    return $this->sessionSegment;
  }
  /**
   * @param Google_Service_AnalyticsReporting_SegmentDefinition
   */
  public function setUserSegment(Google_Service_AnalyticsReporting_SegmentDefinition $userSegment)
  {
    $this->userSegment = $userSegment;
  }
  /**
   * @return Google_Service_AnalyticsReporting_SegmentDefinition
   */
  public function getUserSegment()
  {
    return $this->userSegment;
  }
}
