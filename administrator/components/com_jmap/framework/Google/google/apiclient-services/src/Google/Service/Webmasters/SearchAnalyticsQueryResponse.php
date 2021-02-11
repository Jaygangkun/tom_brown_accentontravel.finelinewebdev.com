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

class Google_Service_Webmasters_SearchAnalyticsQueryResponse extends Google_Collection
{
  protected $collection_key = 'rows';
  public $responseAggregationType;
  protected $rowsType = 'Google_Service_Webmasters_ApiDataRow';
  protected $rowsDataType = 'array';

  public function setResponseAggregationType($responseAggregationType)
  {
    $this->responseAggregationType = $responseAggregationType;
  }
  public function getResponseAggregationType()
  {
    return $this->responseAggregationType;
  }
  /**
   * @param Google_Service_Webmasters_ApiDataRow
   */
  public function setRows($rows)
  {
    $this->rows = $rows;
  }
  /**
   * @return Google_Service_Webmasters_ApiDataRow
   */
  public function getRows()
  {
    return $this->rows;
  }
}
