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

class Google_Service_AnalyticsData_FilterExpressionList extends Google_Collection
{
  protected $collection_key = 'expressions';
  protected $expressionsType = 'Google_Service_AnalyticsData_FilterExpression';
  protected $expressionsDataType = 'array';

  /**
   * @param Google_Service_AnalyticsData_FilterExpression
   */
  public function setExpressions($expressions)
  {
    $this->expressions = $expressions;
  }
  /**
   * @return Google_Service_AnalyticsData_FilterExpression
   */
  public function getExpressions()
  {
    return $this->expressions;
  }
}
