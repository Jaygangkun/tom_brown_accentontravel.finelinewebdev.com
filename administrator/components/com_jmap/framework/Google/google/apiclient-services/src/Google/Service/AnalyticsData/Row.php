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

class Google_Service_AnalyticsData_Row extends Google_Collection
{
  protected $collection_key = 'metricValues';
  protected $dimensionValuesType = 'Google_Service_AnalyticsData_DimensionValue';
  protected $dimensionValuesDataType = 'array';
  protected $metricValuesType = 'Google_Service_AnalyticsData_MetricValue';
  protected $metricValuesDataType = 'array';

  /**
   * @param Google_Service_AnalyticsData_DimensionValue
   */
  public function setDimensionValues($dimensionValues)
  {
    $this->dimensionValues = $dimensionValues;
  }
  /**
   * @return Google_Service_AnalyticsData_DimensionValue
   */
  public function getDimensionValues()
  {
    return $this->dimensionValues;
  }
  /**
   * @param Google_Service_AnalyticsData_MetricValue
   */
  public function setMetricValues($metricValues)
  {
    $this->metricValues = $metricValues;
  }
  /**
   * @return Google_Service_AnalyticsData_MetricValue
   */
  public function getMetricValues()
  {
    return $this->metricValues;
  }
}
