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

class Google_Service_AnalyticsData_NumericFilter extends Google_Model
{
  public $operation;
  protected $valueType = 'Google_Service_AnalyticsData_NumericValue';
  protected $valueDataType = '';

  public function setOperation($operation)
  {
    $this->operation = $operation;
  }
  public function getOperation()
  {
    return $this->operation;
  }
  /**
   * @param Google_Service_AnalyticsData_NumericValue
   */
  public function setValue(Google_Service_AnalyticsData_NumericValue $value)
  {
    $this->value = $value;
  }
  /**
   * @return Google_Service_AnalyticsData_NumericValue
   */
  public function getValue()
  {
    return $this->value;
  }
}
