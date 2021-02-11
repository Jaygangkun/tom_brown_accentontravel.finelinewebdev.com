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

class Google_Service_AnalyticsData_RunReportResponse extends Google_Collection
{
  protected $collection_key = 'totals';
  protected $dimensionHeadersType = 'Google_Service_AnalyticsData_DimensionHeader';
  protected $dimensionHeadersDataType = 'array';
  protected $maximumsType = 'Google_Service_AnalyticsData_Row';
  protected $maximumsDataType = 'array';
  protected $metadataType = 'Google_Service_AnalyticsData_ResponseMetaData';
  protected $metadataDataType = '';
  protected $metricHeadersType = 'Google_Service_AnalyticsData_MetricHeader';
  protected $metricHeadersDataType = 'array';
  protected $minimumsType = 'Google_Service_AnalyticsData_Row';
  protected $minimumsDataType = 'array';
  protected $propertyQuotaType = 'Google_Service_AnalyticsData_PropertyQuota';
  protected $propertyQuotaDataType = '';
  public $rowCount;
  protected $rowsType = 'Google_Service_AnalyticsData_Row';
  protected $rowsDataType = 'array';
  protected $totalsType = 'Google_Service_AnalyticsData_Row';
  protected $totalsDataType = 'array';

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
   * @param Google_Service_AnalyticsData_Row
   */
  public function setMaximums($maximums)
  {
    $this->maximums = $maximums;
  }
  /**
   * @return Google_Service_AnalyticsData_Row
   */
  public function getMaximums()
  {
    return $this->maximums;
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
   * @param Google_Service_AnalyticsData_Row
   */
  public function setMinimums($minimums)
  {
    $this->minimums = $minimums;
  }
  /**
   * @return Google_Service_AnalyticsData_Row
   */
  public function getMinimums()
  {
    return $this->minimums;
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
  public function setRowCount($rowCount)
  {
    $this->rowCount = $rowCount;
  }
  public function getRowCount()
  {
    return $this->rowCount;
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
  /**
   * @param Google_Service_AnalyticsData_Row
   */
  public function setTotals($totals)
  {
    $this->totals = $totals;
  }
  /**
   * @return Google_Service_AnalyticsData_Row
   */
  public function getTotals()
  {
    return $this->totals;
  }
}
