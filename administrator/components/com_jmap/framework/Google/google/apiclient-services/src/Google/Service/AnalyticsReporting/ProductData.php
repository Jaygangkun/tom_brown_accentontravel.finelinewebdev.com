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

class Google_Service_AnalyticsReporting_ProductData extends Google_Model
{
  public $itemRevenue;
  public $productName;
  public $productQuantity;
  public $productSku;

  public function setItemRevenue($itemRevenue)
  {
    $this->itemRevenue = $itemRevenue;
  }
  public function getItemRevenue()
  {
    return $this->itemRevenue;
  }
  public function setProductName($productName)
  {
    $this->productName = $productName;
  }
  public function getProductName()
  {
    return $this->productName;
  }
  public function setProductQuantity($productQuantity)
  {
    $this->productQuantity = $productQuantity;
  }
  public function getProductQuantity()
  {
    return $this->productQuantity;
  }
  public function setProductSku($productSku)
  {
    $this->productSku = $productSku;
  }
  public function getProductSku()
  {
    return $this->productSku;
  }
}
