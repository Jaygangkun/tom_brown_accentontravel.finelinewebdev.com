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

class Google_Service_AnalyticsData_DimensionExpression extends Google_Model
{
  protected $concatenateType = 'Google_Service_AnalyticsData_ConcatenateExpression';
  protected $concatenateDataType = '';
  protected $lowerCaseType = 'Google_Service_AnalyticsData_CaseExpression';
  protected $lowerCaseDataType = '';
  protected $upperCaseType = 'Google_Service_AnalyticsData_CaseExpression';
  protected $upperCaseDataType = '';

  /**
   * @param Google_Service_AnalyticsData_ConcatenateExpression
   */
  public function setConcatenate(Google_Service_AnalyticsData_ConcatenateExpression $concatenate)
  {
    $this->concatenate = $concatenate;
  }
  /**
   * @return Google_Service_AnalyticsData_ConcatenateExpression
   */
  public function getConcatenate()
  {
    return $this->concatenate;
  }
  /**
   * @param Google_Service_AnalyticsData_CaseExpression
   */
  public function setLowerCase(Google_Service_AnalyticsData_CaseExpression $lowerCase)
  {
    $this->lowerCase = $lowerCase;
  }
  /**
   * @return Google_Service_AnalyticsData_CaseExpression
   */
  public function getLowerCase()
  {
    return $this->lowerCase;
  }
  /**
   * @param Google_Service_AnalyticsData_CaseExpression
   */
  public function setUpperCase(Google_Service_AnalyticsData_CaseExpression $upperCase)
  {
    $this->upperCase = $upperCase;
  }
  /**
   * @return Google_Service_AnalyticsData_CaseExpression
   */
  public function getUpperCase()
  {
    return $this->upperCase;
  }
}
