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

class Google_Service_AnalyticsReporting_MetricFilterClause extends Google_Collection
{
  protected $collection_key = 'filters';
  protected $filtersType = 'Google_Service_AnalyticsReporting_MetricFilter';
  protected $filtersDataType = 'array';
  public $operator;

  /**
   * @param Google_Service_AnalyticsReporting_MetricFilter
   */
  public function setFilters($filters)
  {
    $this->filters = $filters;
  }
  /**
   * @return Google_Service_AnalyticsReporting_MetricFilter
   */
  public function getFilters()
  {
    return $this->filters;
  }
  public function setOperator($operator)
  {
    $this->operator = $operator;
  }
  public function getOperator()
  {
    return $this->operator;
  }
}
