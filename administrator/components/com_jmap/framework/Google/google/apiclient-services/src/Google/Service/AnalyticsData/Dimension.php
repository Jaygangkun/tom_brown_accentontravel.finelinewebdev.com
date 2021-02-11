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

class Google_Service_AnalyticsData_Dimension extends Google_Model
{
  protected $dimensionExpressionType = 'Google_Service_AnalyticsData_DimensionExpression';
  protected $dimensionExpressionDataType = '';
  public $name;

  /**
   * @param Google_Service_AnalyticsData_DimensionExpression
   */
  public function setDimensionExpression(Google_Service_AnalyticsData_DimensionExpression $dimensionExpression)
  {
    $this->dimensionExpression = $dimensionExpression;
  }
  /**
   * @return Google_Service_AnalyticsData_DimensionExpression
   */
  public function getDimensionExpression()
  {
    return $this->dimensionExpression;
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
