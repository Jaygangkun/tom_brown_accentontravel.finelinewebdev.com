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

class Google_Service_AnalyticsData_FilterExpression extends Google_Model
{
  protected $andGroupType = 'Google_Service_AnalyticsData_FilterExpressionList';
  protected $andGroupDataType = '';
  protected $filterType = 'Google_Service_AnalyticsData_Filter';
  protected $filterDataType = '';
  protected $notExpressionType = 'Google_Service_AnalyticsData_FilterExpression';
  protected $notExpressionDataType = '';
  protected $orGroupType = 'Google_Service_AnalyticsData_FilterExpressionList';
  protected $orGroupDataType = '';

  /**
   * @param Google_Service_AnalyticsData_FilterExpressionList
   */
  public function setAndGroup(Google_Service_AnalyticsData_FilterExpressionList $andGroup)
  {
    $this->andGroup = $andGroup;
  }
  /**
   * @return Google_Service_AnalyticsData_FilterExpressionList
   */
  public function getAndGroup()
  {
    return $this->andGroup;
  }
  /**
   * @param Google_Service_AnalyticsData_Filter
   */
  public function setFilter(Google_Service_AnalyticsData_Filter $filter)
  {
    $this->filter = $filter;
  }
  /**
   * @return Google_Service_AnalyticsData_Filter
   */
  public function getFilter()
  {
    return $this->filter;
  }
  /**
   * @param Google_Service_AnalyticsData_FilterExpression
   */
  public function setNotExpression(Google_Service_AnalyticsData_FilterExpression $notExpression)
  {
    $this->notExpression = $notExpression;
  }
  /**
   * @return Google_Service_AnalyticsData_FilterExpression
   */
  public function getNotExpression()
  {
    return $this->notExpression;
  }
  /**
   * @param Google_Service_AnalyticsData_FilterExpressionList
   */
  public function setOrGroup(Google_Service_AnalyticsData_FilterExpressionList $orGroup)
  {
    $this->orGroup = $orGroup;
  }
  /**
   * @return Google_Service_AnalyticsData_FilterExpressionList
   */
  public function getOrGroup()
  {
    return $this->orGroup;
  }
}
