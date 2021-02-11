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

class Google_Service_AnalyticsReporting_SegmentDefinition extends Google_Collection
{
  protected $collection_key = 'segmentFilters';
  protected $segmentFiltersType = 'Google_Service_AnalyticsReporting_SegmentFilter';
  protected $segmentFiltersDataType = 'array';

  /**
   * @param Google_Service_AnalyticsReporting_SegmentFilter
   */
  public function setSegmentFilters($segmentFilters)
  {
    $this->segmentFilters = $segmentFilters;
  }
  /**
   * @return Google_Service_AnalyticsReporting_SegmentFilter
   */
  public function getSegmentFilters()
  {
    return $this->segmentFilters;
  }
}
