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

class Google_Service_AnalyticsData_BetweenFilter extends Google_Model
{
  protected $fromValueType = 'Google_Service_AnalyticsData_NumericValue';
  protected $fromValueDataType = '';
  protected $toValueType = 'Google_Service_AnalyticsData_NumericValue';
  protected $toValueDataType = '';

  /**
   * @param Google_Service_AnalyticsData_NumericValue
   */
  public function setFromValue(Google_Service_AnalyticsData_NumericValue $fromValue)
  {
    $this->fromValue = $fromValue;
  }
  /**
   * @return Google_Service_AnalyticsData_NumericValue
   */
  public function getFromValue()
  {
    return $this->fromValue;
  }
  /**
   * @param Google_Service_AnalyticsData_NumericValue
   */
  public function setToValue(Google_Service_AnalyticsData_NumericValue $toValue)
  {
    $this->toValue = $toValue;
  }
  /**
   * @return Google_Service_AnalyticsData_NumericValue
   */
  public function getToValue()
  {
    return $this->toValue;
  }
}
