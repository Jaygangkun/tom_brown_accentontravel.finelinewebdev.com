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

class Google_Service_AnalyticsReporting_SegmentFilterClause extends Google_Model
{
  protected $dimensionFilterType = 'Google_Service_AnalyticsReporting_SegmentDimensionFilter';
  protected $dimensionFilterDataType = '';
  protected $metricFilterType = 'Google_Service_AnalyticsReporting_SegmentMetricFilter';
  protected $metricFilterDataType = '';
  public $not;

  /**
   * @param Google_Service_AnalyticsReporting_SegmentDimensionFilter
   */
  public function setDimensionFilter(Google_Service_AnalyticsReporting_SegmentDimensionFilter $dimensionFilter)
  {
    $this->dimensionFilter = $dimensionFilter;
  }
  /**
   * @return Google_Service_AnalyticsReporting_SegmentDimensionFilter
   */
  public function getDimensionFilter()
  {
    return $this->dimensionFilter;
  }
  /**
   * @param Google_Service_AnalyticsReporting_SegmentMetricFilter
   */
  public function setMetricFilter(Google_Service_AnalyticsReporting_SegmentMetricFilter $metricFilter)
  {
    $this->metricFilter = $metricFilter;
  }
  /**
   * @return Google_Service_AnalyticsReporting_SegmentMetricFilter
   */
  public function getMetricFilter()
  {
    return $this->metricFilter;
  }
  public function setNot($not)
  {
    $this->not = $not;
  }
  public function getNot()
  {
    return $this->not;
  }
}
