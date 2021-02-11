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

class Google_Service_AnalyticsReporting_ReportRow extends Google_Collection
{
  protected $collection_key = 'metrics';
  public $dimensions;
  protected $metricsType = 'Google_Service_AnalyticsReporting_DateRangeValues';
  protected $metricsDataType = 'array';

  public function setDimensions($dimensions)
  {
    $this->dimensions = $dimensions;
  }
  public function getDimensions()
  {
    return $this->dimensions;
  }
  /**
   * @param Google_Service_AnalyticsReporting_DateRangeValues
   */
  public function setMetrics($metrics)
  {
    $this->metrics = $metrics;
  }
  /**
   * @return Google_Service_AnalyticsReporting_DateRangeValues
   */
  public function getMetrics()
  {
    return $this->metrics;
  }
}
