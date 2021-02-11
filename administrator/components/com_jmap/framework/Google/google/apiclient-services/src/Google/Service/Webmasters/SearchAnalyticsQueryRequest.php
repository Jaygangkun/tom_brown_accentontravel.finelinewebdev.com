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

class Google_Service_Webmasters_SearchAnalyticsQueryRequest extends Google_Collection
{
  protected $collection_key = 'dimensions';
  public $aggregationType;
  protected $dimensionFilterGroupsType = 'Google_Service_Webmasters_ApiDimensionFilterGroup';
  protected $dimensionFilterGroupsDataType = 'array';
  public $dimensions;
  public $endDate;
  public $rowLimit;
  public $searchType;
  public $startDate;
  public $startRow;

  public function setAggregationType($aggregationType)
  {
    $this->aggregationType = $aggregationType;
  }
  public function getAggregationType()
  {
    return $this->aggregationType;
  }
  /**
   * @param Google_Service_Webmasters_ApiDimensionFilterGroup
   */
  public function setDimensionFilterGroups($dimensionFilterGroups)
  {
    $this->dimensionFilterGroups = $dimensionFilterGroups;
  }
  /**
   * @return Google_Service_Webmasters_ApiDimensionFilterGroup
   */
  public function getDimensionFilterGroups()
  {
    return $this->dimensionFilterGroups;
  }
  public function setDimensions($dimensions)
  {
    $this->dimensions = $dimensions;
  }
  public function getDimensions()
  {
    return $this->dimensions;
  }
  public function setEndDate($endDate)
  {
    $this->endDate = $endDate;
  }
  public function getEndDate()
  {
    return $this->endDate;
  }
  public function setRowLimit($rowLimit)
  {
    $this->rowLimit = $rowLimit;
  }
  public function getRowLimit()
  {
    return $this->rowLimit;
  }
  public function setSearchType($searchType)
  {
    $this->searchType = $searchType;
  }
  public function getSearchType()
  {
    return $this->searchType;
  }
  public function setStartDate($startDate)
  {
    $this->startDate = $startDate;
  }
  public function getStartDate()
  {
    return $this->startDate;
  }
  public function setStartRow($startRow)
  {
    $this->startRow = $startRow;
  }
  public function getStartRow()
  {
    return $this->startRow;
  }
}
