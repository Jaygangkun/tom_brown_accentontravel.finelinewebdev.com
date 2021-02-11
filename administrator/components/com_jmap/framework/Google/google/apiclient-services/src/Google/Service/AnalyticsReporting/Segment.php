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

class Google_Service_AnalyticsReporting_Segment extends Google_Model
{
  protected $dynamicSegmentType = 'Google_Service_AnalyticsReporting_DynamicSegment';
  protected $dynamicSegmentDataType = '';
  public $segmentId;

  /**
   * @param Google_Service_AnalyticsReporting_DynamicSegment
   */
  public function setDynamicSegment(Google_Service_AnalyticsReporting_DynamicSegment $dynamicSegment)
  {
    $this->dynamicSegment = $dynamicSegment;
  }
  /**
   * @return Google_Service_AnalyticsReporting_DynamicSegment
   */
  public function getDynamicSegment()
  {
    return $this->dynamicSegment;
  }
  public function setSegmentId($segmentId)
  {
    $this->segmentId = $segmentId;
  }
  public function getSegmentId()
  {
    return $this->segmentId;
  }
}
