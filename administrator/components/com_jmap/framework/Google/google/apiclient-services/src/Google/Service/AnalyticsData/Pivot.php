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

class Google_Service_AnalyticsData_Pivot extends Google_Collection
{
  protected $collection_key = 'orderBys';
  public $fieldNames;
  public $limit;
  public $metricAggregations;
  public $offset;
  protected $orderBysType = 'Google_Service_AnalyticsData_OrderBy';
  protected $orderBysDataType = 'array';

  public function setFieldNames($fieldNames)
  {
    $this->fieldNames = $fieldNames;
  }
  public function getFieldNames()
  {
    return $this->fieldNames;
  }
  public function setLimit($limit)
  {
    $this->limit = $limit;
  }
  public function getLimit()
  {
    return $this->limit;
  }
  public function setMetricAggregations($metricAggregations)
  {
    $this->metricAggregations = $metricAggregations;
  }
  public function getMetricAggregations()
  {
    return $this->metricAggregations;
  }
  public function setOffset($offset)
  {
    $this->offset = $offset;
  }
  public function getOffset()
  {
    return $this->offset;
  }
  /**
   * @param Google_Service_AnalyticsData_OrderBy
   */
  public function setOrderBys($orderBys)
  {
    $this->orderBys = $orderBys;
  }
  /**
   * @return Google_Service_AnalyticsData_OrderBy
   */
  public function getOrderBys()
  {
    return $this->orderBys;
  }
}
