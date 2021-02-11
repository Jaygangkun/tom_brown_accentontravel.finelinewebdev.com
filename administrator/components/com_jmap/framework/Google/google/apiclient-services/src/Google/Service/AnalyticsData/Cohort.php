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

class Google_Service_AnalyticsData_Cohort extends Google_Model
{
  protected $dateRangeType = 'Google_Service_AnalyticsData_DateRange';
  protected $dateRangeDataType = '';
  public $dimension;
  public $name;

  /**
   * @param Google_Service_AnalyticsData_DateRange
   */
  public function setDateRange(Google_Service_AnalyticsData_DateRange $dateRange)
  {
    $this->dateRange = $dateRange;
  }
  /**
   * @return Google_Service_AnalyticsData_DateRange
   */
  public function getDateRange()
  {
    return $this->dateRange;
  }
  public function setDimension($dimension)
  {
    $this->dimension = $dimension;
  }
  public function getDimension()
  {
    return $this->dimension;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
}
