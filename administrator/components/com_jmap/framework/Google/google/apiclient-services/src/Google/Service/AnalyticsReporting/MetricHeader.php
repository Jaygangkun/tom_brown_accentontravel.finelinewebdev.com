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

class Google_Service_AnalyticsReporting_MetricHeader extends Google_Collection
{
  protected $collection_key = 'pivotHeaders';
  protected $metricHeaderEntriesType = 'Google_Service_AnalyticsReporting_MetricHeaderEntry';
  protected $metricHeaderEntriesDataType = 'array';
  protected $pivotHeadersType = 'Google_Service_AnalyticsReporting_PivotHeader';
  protected $pivotHeadersDataType = 'array';

  /**
   * @param Google_Service_AnalyticsReporting_MetricHeaderEntry
   */
  public function setMetricHeaderEntries($metricHeaderEntries)
  {
    $this->metricHeaderEntries = $metricHeaderEntries;
  }
  /**
   * @return Google_Service_AnalyticsReporting_MetricHeaderEntry
   */
  public function getMetricHeaderEntries()
  {
    return $this->metricHeaderEntries;
  }
  /**
   * @param Google_Service_AnalyticsReporting_PivotHeader
   */
  public function setPivotHeaders($pivotHeaders)
  {
    $this->pivotHeaders = $pivotHeaders;
  }
  /**
   * @return Google_Service_AnalyticsReporting_PivotHeader
   */
  public function getPivotHeaders()
  {
    return $this->pivotHeaders;
  }
}
