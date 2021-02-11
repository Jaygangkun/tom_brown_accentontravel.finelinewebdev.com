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

class Google_Service_AnalyticsReporting_ReportRequest extends Google_Collection
{
  protected $collection_key = 'segments';
  protected $cohortGroupType = 'Google_Service_AnalyticsReporting_CohortGroup';
  protected $cohortGroupDataType = '';
  protected $dateRangesType = 'Google_Service_AnalyticsReporting_DateRange';
  protected $dateRangesDataType = 'array';
  protected $dimensionFilterClausesType = 'Google_Service_AnalyticsReporting_DimensionFilterClause';
  protected $dimensionFilterClausesDataType = 'array';
  protected $dimensionsType = 'Google_Service_AnalyticsReporting_Dimension';
  protected $dimensionsDataType = 'array';
  public $filtersExpression;
  public $hideTotals;
  public $hideValueRanges;
  public $includeEmptyRows;
  protected $metricFilterClausesType = 'Google_Service_AnalyticsReporting_MetricFilterClause';
  protected $metricFilterClausesDataType = 'array';
  protected $metricsType = 'Google_Service_AnalyticsReporting_Metric';
  protected $metricsDataType = 'array';
  protected $orderBysType = 'Google_Service_AnalyticsReporting_OrderBy';
  protected $orderBysDataType = 'array';
  public $pageSize;
  public $pageToken;
  protected $pivotsType = 'Google_Service_AnalyticsReporting_Pivot';
  protected $pivotsDataType = 'array';
  public $samplingLevel;
  protected $segmentsType = 'Google_Service_AnalyticsReporting_Segment';
  protected $segmentsDataType = 'array';
  public $viewId;

  /**
   * @param Google_Service_AnalyticsReporting_CohortGroup
   */
  public function setCohortGroup(Google_Service_AnalyticsReporting_CohortGroup $cohortGroup)
  {
    $this->cohortGroup = $cohortGroup;
  }
  /**
   * @return Google_Service_AnalyticsReporting_CohortGroup
   */
  public function getCohortGroup()
  {
    return $this->cohortGroup;
  }
  /**
   * @param Google_Service_AnalyticsReporting_DateRange
   */
  public function setDateRanges($dateRanges)
  {
    $this->dateRanges = $dateRanges;
  }
  /**
   * @return Google_Service_AnalyticsReporting_DateRange
   */
  public function getDateRanges()
  {
    return $this->dateRanges;
  }
  /**
   * @param Google_Service_AnalyticsReporting_DimensionFilterClause
   */
  public function setDimensionFilterClauses($dimensionFilterClauses)
  {
    $this->dimensionFilterClauses = $dimensionFilterClauses;
  }
  /**
   * @return Google_Service_AnalyticsReporting_DimensionFilterClause
   */
  public function getDimensionFilterClauses()
  {
    return $this->dimensionFilterClauses;
  }
  /**
   * @param Google_Service_AnalyticsReporting_Dimension
   */
  public function setDimensions($dimensions)
  {
    $this->dimensions = $dimensions;
  }
  /**
   * @return Google_Service_AnalyticsReporting_Dimension
   */
  public function getDimensions()
  {
    return $this->dimensions;
  }
  public function setFiltersExpression($filtersExpression)
  {
    $this->filtersExpression = $filtersExpression;
  }
  public function getFiltersExpression()
  {
    return $this->filtersExpression;
  }
  public function setHideTotals($hideTotals)
  {
    $this->hideTotals = $hideTotals;
  }
  public function getHideTotals()
  {
    return $this->hideTotals;
  }
  public function setHideValueRanges($hideValueRanges)
  {
    $this->hideValueRanges = $hideValueRanges;
  }
  public function getHideValueRanges()
  {
    return $this->hideValueRanges;
  }
  public function setIncludeEmptyRows($includeEmptyRows)
  {
    $this->includeEmptyRows = $includeEmptyRows;
  }
  public function getIncludeEmptyRows()
  {
    return $this->includeEmptyRows;
  }
  /**
   * @param Google_Service_AnalyticsReporting_MetricFilterClause
   */
  public function setMetricFilterClauses($metricFilterClauses)
  {
    $this->metricFilterClauses = $metricFilterClauses;
  }
  /**
   * @return Google_Service_AnalyticsReporting_MetricFilterClause
   */
  public function getMetricFilterClauses()
  {
    return $this->metricFilterClauses;
  }
  /**
   * @param Google_Service_AnalyticsReporting_Metric
   */
  public function setMetrics($metrics)
  {
    $this->metrics = $metrics;
  }
  /**
   * @return Google_Service_AnalyticsReporting_Metric
   */
  public function getMetrics()
  {
    return $this->metrics;
  }
  /**
   * @param Google_Service_AnalyticsReporting_OrderBy
   */
  public function setOrderBys($orderBys)
  {
    $this->orderBys = $orderBys;
  }
  /**
   * @return Google_Service_AnalyticsReporting_OrderBy
   */
  public function getOrderBys()
  {
    return $this->orderBys;
  }
  public function setPageSize($pageSize)
  {
    $this->pageSize = $pageSize;
  }
  public function getPageSize()
  {
    return $this->pageSize;
  }
  public function setPageToken($pageToken)
  {
    $this->pageToken = $pageToken;
  }
  public function getPageToken()
  {
    return $this->pageToken;
  }
  /**
   * @param Google_Service_AnalyticsReporting_Pivot
   */
  public function setPivots($pivots)
  {
    $this->pivots = $pivots;
  }
  /**
   * @return Google_Service_AnalyticsReporting_Pivot
   */
  public function getPivots()
  {
    return $this->pivots;
  }
  public function setSamplingLevel($samplingLevel)
  {
    $this->samplingLevel = $samplingLevel;
  }
  public function getSamplingLevel()
  {
    return $this->samplingLevel;
  }
  /**
   * @param Google_Service_AnalyticsReporting_Segment
   */
  public function setSegments($segments)
  {
    $this->segments = $segments;
  }
  /**
   * @return Google_Service_AnalyticsReporting_Segment
   */
  public function getSegments()
  {
    return $this->segments;
  }
  public function setViewId($viewId)
  {
    $this->viewId = $viewId;
  }
  public function getViewId()
  {
    return $this->viewId;
  }
}
