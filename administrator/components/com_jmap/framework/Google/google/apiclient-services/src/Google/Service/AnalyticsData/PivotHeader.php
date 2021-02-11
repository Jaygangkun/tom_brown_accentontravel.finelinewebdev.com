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

class Google_Service_AnalyticsData_PivotHeader extends Google_Collection
{
  protected $collection_key = 'pivotDimensionHeaders';
  protected $pivotDimensionHeadersType = 'Google_Service_AnalyticsData_PivotDimensionHeader';
  protected $pivotDimensionHeadersDataType = 'array';
  public $rowCount;

  /**
   * @param Google_Service_AnalyticsData_PivotDimensionHeader
   */
  public function setPivotDimensionHeaders($pivotDimensionHeaders)
  {
    $this->pivotDimensionHeaders = $pivotDimensionHeaders;
  }
  /**
   * @return Google_Service_AnalyticsData_PivotDimensionHeader
   */
  public function getPivotDimensionHeaders()
  {
    return $this->pivotDimensionHeaders;
  }
  public function setRowCount($rowCount)
  {
    $this->rowCount = $rowCount;
  }
  public function getRowCount()
  {
    return $this->rowCount;
  }
}
