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

class Google_Service_AnalyticsData_DimensionMetadata extends Google_Collection
{
  protected $collection_key = 'deprecatedApiNames';
  public $apiName;
  public $deprecatedApiNames;
  public $description;
  public $uiName;

  public function setApiName($apiName)
  {
    $this->apiName = $apiName;
  }
  public function getApiName()
  {
    return $this->apiName;
  }
  public function setDeprecatedApiNames($deprecatedApiNames)
  {
    $this->deprecatedApiNames = $deprecatedApiNames;
  }
  public function getDeprecatedApiNames()
  {
    return $this->deprecatedApiNames;
  }
  public function setDescription($description)
  {
    $this->description = $description;
  }
  public function getDescription()
  {
    return $this->description;
  }
  public function setUiName($uiName)
  {
    $this->uiName = $uiName;
  }
  public function getUiName()
  {
    return $this->uiName;
  }
}
