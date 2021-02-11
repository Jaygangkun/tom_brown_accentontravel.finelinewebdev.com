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

class Google_Service_AnalyticsReporting_SegmentMetricFilter extends Google_Model
{
  public $comparisonValue;
  public $maxComparisonValue;
  public $metricName;
  public $operator;
  public $scope;

  public function setComparisonValue($comparisonValue)
  {
    $this->comparisonValue = $comparisonValue;
  }
  public function getComparisonValue()
  {
    return $this->comparisonValue;
  }
  public function setMaxComparisonValue($maxComparisonValue)
  {
    $this->maxComparisonValue = $maxComparisonValue;
  }
  public function getMaxComparisonValue()
  {
    return $this->maxComparisonValue;
  }
  public function setMetricName($metricName)
  {
    $this->metricName = $metricName;
  }
  public function getMetricName()
  {
    return $this->metricName;
  }
  public function setOperator($operator)
  {
    $this->operator = $operator;
  }
  public function getOperator()
  {
    return $this->operator;
  }
  public function setScope($scope)
  {
    $this->scope = $scope;
  }
  public function getScope()
  {
    return $this->scope;
  }
}
