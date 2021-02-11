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

class Google_Service_AnalyticsReporting_OrderBy extends Google_Model
{
  public $fieldName;
  public $orderType;
  public $sortOrder;

  public function setFieldName($fieldName)
  {
    $this->fieldName = $fieldName;
  }
  public function getFieldName()
  {
    return $this->fieldName;
  }
  public function setOrderType($orderType)
  {
    $this->orderType = $orderType;
  }
  public function getOrderType()
  {
    return $this->orderType;
  }
  public function setSortOrder($sortOrder)
  {
    $this->sortOrder = $sortOrder;
  }
  public function getSortOrder()
  {
    return $this->sortOrder;
  }
}
