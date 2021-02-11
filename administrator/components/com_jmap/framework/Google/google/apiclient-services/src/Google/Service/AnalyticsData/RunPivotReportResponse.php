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

class Google_Service_AnalyticsData_RunPivotReportResponse extends Google_Collection
{
  protected $collection_key = 'rows';
  protected $aggregatesType = 'Google_Service_AnalyticsData_Row';
  protected $aggregatesDataType = 'array';
  protected $dimensionHeadersType = 'Google_Service_AnalyticsData_DimensionHeader';
  protected $dimensionHeadersDataType = 'array';
  protected $metadataType = 'Google_Service_AnalyticsData_ResponseMetaData';
  protected $metadataDataType = '';
  protected $metricHeadersType = 'Google_Service_AnalyticsData_MetricHeader';
  protected $metricHeadersDataType = 'array';
  protected $pivotHeadersType = 'Google_Service_AnalyticsData_PivotHeader';
  protected $pivotHeadersDataType = 'array';
  protected $propertyQuotaType = 'Google_Service_AnalyticsData_PropertyQuota';
  protected $propertyQuotaDataType = '';
  protected $rowsType = 'Google_Service_AnalyticsData_Row';
  protected $rowsDataType = 'array';

  /**
   * @param Google_Service_AnalyticsData_Row
   */
  public function setAggregates($aggregates)
  {
    $this->aggregates = $aggregates;
  }
  /**
   * @return Google_Service_AnalyticsData_Row
   */
  public function getAggregates()
  {
    return $this->aggregates;
  }
  /**
   * @param Google_Service_AnalyticsData_DimensionHeader
   */
  public function setDimensionHeaders($dimensionHeaders)
  {
    $this->dimensionHeaders = $dimensionHeaders;
  }
  /**
   * @return Google_Service_AnalyticsData_DimensionHeader
   */
  public function getDimensionHeaders()
  {
    return $this->dimensionHeaders;
  }
  /**
   * @param Google_Service_AnalyticsData_ResponseMetaData
   */
  public function setMetadata(Google_Service_AnalyticsData_ResponseMetaData $metadata)
  {
    $this->metadata = $metadata;
  }
  /**
   * @return Google_Service_AnalyticsData_ResponseMetaData
   */
  public function getMetadata()
  {
    return $this->metadata;
  }
  /**
   * @param Google_Service_AnalyticsData_MetricHeader
   */
  public function setMetricHeaders($metricHeaders)
  {
    $this->metricHeaders = $metricHeaders;
  }
  /**
   * @return Google_Service_AnalyticsData_MetricHeader
   */
  public function getMetricHeaders()
  {
    return $this->metricHeaders;
  }
  /**
   * @param Google_Service_AnalyticsData_PivotHeader
   */
  public function setPivotHeaders($pivotHeaders)
  {
    $this->pivotHeaders = $pivotHeaders;
  }
  /**
   * @return Google_Service_AnalyticsData_PivotHeader
   */
  public function getPivotHeaders()
  {
    return $this->pivotHeaders;
  }
  /**
   * @param Google_Service_AnalyticsData_PropertyQuota
   */
  public function setPropertyQuota(Google_Service_AnalyticsData_PropertyQuota $propertyQuota)
  {
    $this->propertyQuota = $propertyQuota;
  }
  /**
   * @return Google_Service_AnalyticsData_PropertyQuota
   */
  public function getPropertyQuota()
  {
    return $this->propertyQuota;
  }
  /**
   * @param Google_Service_AnalyticsData_Row
   */
  public function setRows($rows)
  {
    $this->rows = $rows;
  }
  /**
   * @return Google_Service_AnalyticsData_Row
   */
  public function getRows()
  {
    return $this->rows;
  }
}
