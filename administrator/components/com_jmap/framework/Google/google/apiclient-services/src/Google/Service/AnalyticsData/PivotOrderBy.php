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

class Google_Service_AnalyticsData_PivotOrderBy extends Google_Collection
{
  protected $collection_key = 'pivotSelections';
  public $metricName;
  protected $pivotSelectionsType = 'Google_Service_AnalyticsData_PivotSelection';
  protected $pivotSelectionsDataType = 'array';

  public function setMetricName($metricName)
  {
    $this->metricName = $metricName;
  }
  public function getMetricName()
  {
    return $this->metricName;
  }
  /**
   * @param Google_Service_AnalyticsData_PivotSelection
   */
  public function setPivotSelections($pivotSelections)
  {
    $this->pivotSelections = $pivotSelections;
  }
  /**
   * @return Google_Service_AnalyticsData_PivotSelection
   */
  public function getPivotSelections()
  {
    return $this->pivotSelections;
  }
}
