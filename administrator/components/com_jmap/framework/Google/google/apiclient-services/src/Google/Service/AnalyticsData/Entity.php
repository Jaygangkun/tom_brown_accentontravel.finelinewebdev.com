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

class Google_Service_AnalyticsData_Entity extends Google_Model
{
  public $propertyId;

  public function setPropertyId($propertyId)
  {
    $this->propertyId = $propertyId;
  }
  public function getPropertyId()
  {
    return $this->propertyId;
  }
}