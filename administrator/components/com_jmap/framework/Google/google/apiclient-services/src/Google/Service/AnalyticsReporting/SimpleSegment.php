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

class Google_Service_AnalyticsReporting_SimpleSegment extends Google_Collection
{
  protected $collection_key = 'orFiltersForSegment';
  protected $orFiltersForSegmentType = 'Google_Service_AnalyticsReporting_OrFiltersForSegment';
  protected $orFiltersForSegmentDataType = 'array';

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
