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

class Google_Service_AnalyticsReporting_Report extends Google_Model
{
  protected $columnHeaderType = 'Google_Service_AnalyticsReporting_ColumnHeader';
  protected $columnHeaderDataType = '';
  protected $dataType = 'Google_Service_AnalyticsReporting_ReportData';
  protected $dataDataType = '';
  public $nextPageToken;

  /**
   * @param Google_Service_AnalyticsReporting_ColumnHeader
   */
  public function setColumnHeader(Google_Service_AnalyticsReporting_ColumnHeader $columnHeader)
  {
    $this->columnHeader = $columnHeader;
  }
  /**
   * @return Google_Service_AnalyticsReporting_ColumnHeader
   */
  public function getColumnHeader()
  {
    return $this->columnHeader;
  }
  /**
   * @param Google_Service_AnalyticsReporting_ReportData
   */
  public function setData(Google_Service_AnalyticsReporting_ReportData $data)
  {
    $this->data = $data;
  }
  /**
   * @return Google_Service_AnalyticsReporting_ReportData
   */
  public function getData()
  {
    return $this->data;
  }
  public function setNextPageToken($nextPageToken)
  {
    $this->nextPageToken = $nextPageToken;
  }
  public function getNextPageToken()
  {
    return $this->nextPageToken;
  }
}
