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

class Google_Service_AnalyticsReporting_ReportData extends Google_Collection
{
  protected $collection_key = 'totals';
  public $dataLastRefreshed;
  public $isDataGolden;
  protected $maximumsType = 'Google_Service_AnalyticsReporting_DateRangeValues';
  protected $maximumsDataType = 'array';
  protected $minimumsType = 'Google_Service_AnalyticsReporting_DateRangeValues';
  protected $minimumsDataType = 'array';
  public $rowCount;
  protected $rowsType = 'Google_Service_AnalyticsReporting_ReportRow';
  protected $rowsDataType = 'array';
  public $samplesReadCounts;
  public $samplingSpaceSizes;
  protected $totalsType = 'Google_Service_AnalyticsReporting_DateRangeValues';
  protected $totalsDataType = 'array';

  public function setDataLastRefreshed($dataLastRefreshed)
  {
    $this->dataLastRefreshed = $dataLastRefreshed;
  }
  public function getDataLastRefreshed()
  {
    return $this->dataLastRefreshed;
  }
  public function setIsDataGolden($isDataGolden)
  {
    $this->isDataGolden = $isDataGolden;
  }
  public function getIsDataGolden()
  {
    return $this->isDataGolden;
  }
  /**
   * @param Google_Service_AnalyticsReporting_DateRangeValues
   */
  public function setMaximums($maximums)
  {
    $this->maximums = $maximums;
  }
  /**
   * @return Google_Service_AnalyticsReporting_DateRangeValues
   */
  public function getMaximums()
  {
    return $this->maximums;
  }
  /**
   * @param Google_Service_AnalyticsReporting_DateRangeValues
   */
  public function setMinimums($minimums)
  {
    $this->minimums = $minimums;
  }
  /**
   * @return Google_Service_AnalyticsReporting_DateRangeValues
   */
  public function getMinimums()
  {
    return $this->minimums;
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
   * @param Google_Service_AnalyticsReporting_ReportRow
   */
  public function setRows($rows)
  {
    $this->rows = $rows;
  }
  /**
   * @return Google_Service_AnalyticsReporting_ReportRow
   */
  public function getRows()
  {
    return $this->rows;
  }
  public function setSamplesReadCounts($samplesReadCounts)
  {
    $this->samplesReadCounts = $samplesReadCounts;
  }
  public function getSamplesReadCounts()
  {
    return $this->samplesReadCounts;
  }
  public function setSamplingSpaceSizes($samplingSpaceSizes)
  {
    $this->samplingSpaceSizes = $samplingSpaceSizes;
  }
  public function getSamplingSpaceSizes()
  {
    return $this->samplingSpaceSizes;
  }
  /**
   * @param Google_Service_AnalyticsReporting_DateRangeValues
   */
  public function setTotals($totals)
  {
    $this->totals = $totals;
  }
  /**
   * @return Google_Service_AnalyticsReporting_DateRangeValues
   */
  public function getTotals()
  {
    return $this->totals;
  }
}
