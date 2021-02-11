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

class Google_Service_AnalyticsData_OrderBy extends Google_Model
{
  public $desc;
  protected $dimensionType = 'Google_Service_AnalyticsData_DimensionOrderBy';
  protected $dimensionDataType = '';
  protected $metricType = 'Google_Service_AnalyticsData_MetricOrderBy';
  protected $metricDataType = '';
  protected $pivotType = 'Google_Service_AnalyticsData_PivotOrderBy';
  protected $pivotDataType = '';

  public function setDesc($desc)
  {
    $this->desc = $desc;
  }
  public function getDesc()
  {
    return $this->desc;
  }
  /**
   * @param Google_Service_AnalyticsData_DimensionOrderBy
   */
  public function setDimension(Google_Service_AnalyticsData_DimensionOrderBy $dimension)
  {
    $this->dimension = $dimension;
  }
  /**
   * @return Google_Service_AnalyticsData_DimensionOrderBy
   */
  public function getDimension()
  {
    return $this->dimension;
  }
  /**
   * @param Google_Service_AnalyticsData_MetricOrderBy
   */
  public function setMetric(Google_Service_AnalyticsData_MetricOrderBy $metric)
  {
    $this->metric = $metric;
  }
  /**
   * @return Google_Service_AnalyticsData_MetricOrderBy
   */
  public function getMetric()
  {
    return $this->metric;
  }
  /**
   * @param Google_Service_AnalyticsData_PivotOrderBy
   */
  public function setPivot(Google_Service_AnalyticsData_PivotOrderBy $pivot)
  {
    $this->pivot = $pivot;
  }
  /**
   * @return Google_Service_AnalyticsData_PivotOrderBy
   */
  public function getPivot()
  {
    return $this->pivot;
  }
}
