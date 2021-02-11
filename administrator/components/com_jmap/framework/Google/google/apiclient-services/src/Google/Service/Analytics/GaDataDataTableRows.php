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

class Google_Service_Analytics_GaDataDataTableRows extends Google_Collection
{
  protected $collection_key = 'c';
  protected $cType = 'Google_Service_Analytics_GaDataDataTableRowsC';
  protected $cDataType = 'array';

  /**
   * @param Google_Service_Analytics_GaDataDataTableRowsC
   */
  public function setC($c)
  {
    $this->c = $c;
  }
  /**
   * @return Google_Service_Analytics_GaDataDataTableRowsC
   */
  public function getC()
  {
    return $this->c;
  }
}
