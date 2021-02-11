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

class Google_Service_AnalyticsData_Filter extends Google_Model
{
  protected $betweenFilterType = 'Google_Service_AnalyticsData_BetweenFilter';
  protected $betweenFilterDataType = '';
  public $fieldName;
  protected $inListFilterType = 'Google_Service_AnalyticsData_InListFilter';
  protected $inListFilterDataType = '';
  public $nullFilter;
  protected $numericFilterType = 'Google_Service_AnalyticsData_NumericFilter';
  protected $numericFilterDataType = '';
  protected $stringFilterType = 'Google_Service_AnalyticsData_StringFilter';
  protected $stringFilterDataType = '';

  /**
   * @param Google_Service_AnalyticsData_BetweenFilter
   */
  public function setBetweenFilter(Google_Service_AnalyticsData_BetweenFilter $betweenFilter)
  {
    $this->betweenFilter = $betweenFilter;
  }
  /**
   * @return Google_Service_AnalyticsData_BetweenFilter
   */
  public function getBetweenFilter()
  {
    return $this->betweenFilter;
  }
  public function setFieldName($fieldName)
  {
    $this->fieldName = $fieldName;
  }
  public function getFieldName()
  {
    return $this->fieldName;
  }
  /**
   * @param Google_Service_AnalyticsData_InListFilter
   */
  public function setInListFilter(Google_Service_AnalyticsData_InListFilter $inListFilter)
  {
    $this->inListFilter = $inListFilter;
  }
  /**
   * @return Google_Service_AnalyticsData_InListFilter
   */
  public function getInListFilter()
  {
    return $this->inListFilter;
  }
  public function setNullFilter($nullFilter)
  {
    $this->nullFilter = $nullFilter;
  }
  public function getNullFilter()
  {
    return $this->nullFilter;
  }
  /**
   * @param Google_Service_AnalyticsData_NumericFilter
   */
  public function setNumericFilter(Google_Service_AnalyticsData_NumericFilter $numericFilter)
  {
    $this->numericFilter = $numericFilter;
  }
  /**
   * @return Google_Service_AnalyticsData_NumericFilter
   */
  public function getNumericFilter()
  {
    return $this->numericFilter;
  }
  /**
   * @param Google_Service_AnalyticsData_StringFilter
   */
  public function setStringFilter(Google_Service_AnalyticsData_StringFilter $stringFilter)
  {
    $this->stringFilter = $stringFilter;
  }
  /**
   * @return Google_Service_AnalyticsData_StringFilter
   */
  public function getStringFilter()
  {
    return $this->stringFilter;
  }
}
