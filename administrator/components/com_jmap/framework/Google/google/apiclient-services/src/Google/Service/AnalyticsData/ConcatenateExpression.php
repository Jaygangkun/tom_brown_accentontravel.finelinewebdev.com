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

class Google_Service_AnalyticsData_ConcatenateExpression extends Google_Collection
{
  protected $collection_key = 'dimensionNames';
  public $delimiter;
  public $dimensionNames;

  public function setDelimiter($delimiter)
  {
    $this->delimiter = $delimiter;
  }
  public function getDelimiter()
  {
    return $this->delimiter;
  }
  public function setDimensionNames($dimensionNames)
  {
    $this->dimensionNames = $dimensionNames;
  }
  public function getDimensionNames()
  {
    return $this->dimensionNames;
  }
}
