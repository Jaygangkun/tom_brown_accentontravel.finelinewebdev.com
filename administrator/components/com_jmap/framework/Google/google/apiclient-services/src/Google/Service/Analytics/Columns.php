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

class Google_Service_Analytics_Columns extends Google_Collection
{
  protected $collection_key = 'items';
  public $attributeNames;
  public $etag;
  protected $itemsType = 'Google_Service_Analytics_Column';
  protected $itemsDataType = 'array';
  public $kind;
  public $totalResults;

  public function setAttributeNames($attributeNames)
  {
    $this->attributeNames = $attributeNames;
  }
  public function getAttributeNames()
  {
    return $this->attributeNames;
  }
  public function setEtag($etag)
  {
    $this->etag = $etag;
  }
  public function getEtag()
  {
    return $this->etag;
  }
  /**
   * @param Google_Service_Analytics_Column
   */
  public function setItems($items)
  {
    $this->items = $items;
  }
  /**
   * @return Google_Service_Analytics_Column
   */
  public function getItems()
  {
    return $this->items;
  }
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  public function getKind()
  {
    return $this->kind;
  }
  public function setTotalResults($totalResults)
  {
    $this->totalResults = $totalResults;
  }
  public function getTotalResults()
  {
    return $this->totalResults;
  }
}
