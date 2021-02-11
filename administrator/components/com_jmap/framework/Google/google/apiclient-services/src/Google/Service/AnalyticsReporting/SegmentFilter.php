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

class Google_Service_AnalyticsReporting_SegmentFilter extends Google_Model
{
  public $not;
  protected $sequenceSegmentType = 'Google_Service_AnalyticsReporting_SequenceSegment';
  protected $sequenceSegmentDataType = '';
  protected $simpleSegmentType = 'Google_Service_AnalyticsReporting_SimpleSegment';
  protected $simpleSegmentDataType = '';

  public function setNot($not)
  {
    $this->not = $not;
  }
  public function getNot()
  {
    return $this->not;
  }
  /**
   * @param Google_Service_AnalyticsReporting_SequenceSegment
   */
  public function setSequenceSegment(Google_Service_AnalyticsReporting_SequenceSegment $sequenceSegment)
  {
    $this->sequenceSegment = $sequenceSegment;
  }
  /**
   * @return Google_Service_AnalyticsReporting_SequenceSegment
   */
  public function getSequenceSegment()
  {
    return $this->sequenceSegment;
  }
  /**
   * @param Google_Service_AnalyticsReporting_SimpleSegment
   */
  public function setSimpleSegment(Google_Service_AnalyticsReporting_SimpleSegment $simpleSegment)
  {
    $this->simpleSegment = $simpleSegment;
  }
  /**
   * @return Google_Service_AnalyticsReporting_SimpleSegment
   */
  public function getSimpleSegment()
  {
    return $this->simpleSegment;
  }
}
