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

class Google_Service_Analytics_GaDataDataTable extends Google_Collection
{
  protected $collection_key = 'rows';
  protected $colsType = 'Google_Service_Analytics_GaDataDataTableCols';
  protected $colsDataType = 'array';
  protected $rowsType = 'Google_Service_Analytics_GaDataDataTableRows';
  protected $rowsDataType = 'array';

  /**
   * @param Google_Service_Analytics_GaDataDataTableCols
   */
  public function setCols($cols)
  {
    $this->cols = $cols;
  }
  /**
   * @return Google_Service_Analytics_GaDataDataTableCols
   */
  public function getCols()
  {
    return $this->cols;
  }
  /**
   * @param Google_Service_Analytics_GaDataDataTableRows
   */
  public function setRows($rows)
  {
    $this->rows = $rows;
  }
  /**
   * @return Google_Service_Analytics_GaDataDataTableRows
   */
  public function getRows()
  {
    return $this->rows;
  }
}
