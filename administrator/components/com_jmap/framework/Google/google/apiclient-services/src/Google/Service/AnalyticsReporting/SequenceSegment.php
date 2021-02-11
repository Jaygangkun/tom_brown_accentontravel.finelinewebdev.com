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

class Google_Service_AnalyticsReporting_SequenceSegment extends Google_Collection
{
  protected $collection_key = 'segmentSequenceSteps';
  public $firstStepShouldMatchFirstHit;
  protected $segmentSequenceStepsType = 'Google_Service_AnalyticsReporting_SegmentSequenceStep';
  protected $segmentSequenceStepsDataType = 'array';

  public function setFirstStepShouldMatchFirstHit($firstStepShouldMatchFirstHit)
  {
    $this->firstStepShouldMatchFirstHit = $firstStepShouldMatchFirstHit;
  }
  public function getFirstStepShouldMatchFirstHit()
  {
    return $this->firstStepShouldMatchFirstHit;
  }
  /**
   * @param Google_Service_AnalyticsReporting_SegmentSequenceStep
   */
  public function setSegmentSequenceSteps($segmentSequenceSteps)
  {
    $this->segmentSequenceSteps = $segmentSequenceSteps;
  }
  /**
   * @return Google_Service_AnalyticsReporting_SegmentSequenceStep
   */
  public function getSegmentSequenceSteps()
  {
    return $this->segmentSequenceSteps;
  }
}
