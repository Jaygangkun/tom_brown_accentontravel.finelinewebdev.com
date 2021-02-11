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

class Google_Service_AnalyticsReporting_ColumnHeader extends Google_Collection
{
  protected $collection_key = 'dimensions';
  public $dimensions;
  protected $metricHeaderType = 'Google_Service_AnalyticsReporting_MetricHeader';
  protected $metricHeaderDataType = '';

  public function setDimensions($dimensions)
  {
    $this->dimensions = $dimensions;
  }
  public function getDimensions()
  {
    return $this->dimensions;
  }
  /**
   * @param Google_Service_AnalyticsReporting_MetricHeader
   */
  public function setMetricHeader(Google_Service_AnalyticsReporting_MetricHeader $metricHeader)
  {
    $this->metricHeader = $metricHeader;
  }
  /**
   * @return Google_Service_AnalyticsReporting_MetricHeader
   */
  public function getMetricHeader()
  {
    return $this->metricHeader;
  }
}
