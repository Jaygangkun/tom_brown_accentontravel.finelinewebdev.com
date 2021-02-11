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

class Google_Service_AnalyticsReporting_SegmentSequenceStep extends Google_Collection
{
  protected $collection_key = 'orFiltersForSegment';
  public $matchType;
  protected $orFiltersForSegmentType = 'Google_Service_AnalyticsReporting_OrFiltersForSegment';
  protected $orFiltersForSegmentDataType = 'array';

  public function setMatchType($matchType)
  {
    $this->matchType = $matchType;
  }
  public function getMatchType()
  {
    return $this->matchType;
  }
  /**
   * @param Google_Service_AnalyticsReporting_OrFiltersForSegment
   */
  public function setOrFiltersForSegment($orFiltersForSegment)
  {
    $this->orFiltersForSegment = $orFiltersForSegment;
  }
  /**
   * @return Google_Service_AnalyticsReporting_OrFiltersForSegment
   */
  public function getOrFiltersForSegment()
  {
    return $this->orFiltersForSegment;
  }
}
