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

class Google_Service_AnalyticsReporting_DateRangeValues extends Google_Collection
{
  protected $collection_key = 'values';
  protected $pivotValueRegionsType = 'Google_Service_AnalyticsReporting_PivotValueRegion';
  protected $pivotValueRegionsDataType = 'array';
  public $values;

  /**
   * @param Google_Service_AnalyticsReporting_PivotValueRegion
   */
  public function setPivotValueRegions($pivotValueRegions)
  {
    $this->pivotValueRegions = $pivotValueRegions;
  }
  /**
   * @return Google_Service_AnalyticsReporting_PivotValueRegion
   */
  public function getPivotValueRegions()
  {
    return $this->pivotValueRegions;
  }
  public function setValues($values)
  {
    $this->values = $values;
  }
  public function getValues()
  {
    return $this->values;
  }
}
