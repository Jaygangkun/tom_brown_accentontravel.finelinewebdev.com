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

class Google_Service_AnalyticsReporting_OrFiltersForSegment extends Google_Collection
{
  protected $collection_key = 'segmentFilterClauses';
  protected $segmentFilterClausesType = 'Google_Service_AnalyticsReporting_SegmentFilterClause';
  protected $segmentFilterClausesDataType = 'array';

  /**
   * @param Google_Service_AnalyticsReporting_SegmentFilterClause
   */
  public function setSegmentFilterClauses($segmentFilterClauses)
  {
    $this->segmentFilterClauses = $segmentFilterClauses;
  }
  /**
   * @return Google_Service_AnalyticsReporting_SegmentFilterClause
   */
  public function getSegmentFilterClauses()
  {
    return $this->segmentFilterClauses;
  }
}
