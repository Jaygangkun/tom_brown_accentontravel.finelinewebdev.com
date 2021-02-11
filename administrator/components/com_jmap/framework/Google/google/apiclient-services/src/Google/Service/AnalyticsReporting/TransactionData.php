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

class Google_Service_AnalyticsReporting_TransactionData extends Google_Model
{
  public $transactionId;
  public $transactionRevenue;
  public $transactionShipping;
  public $transactionTax;

  public function setTransactionId($transactionId)
  {
    $this->transactionId = $transactionId;
  }
  public function getTransactionId()
  {
    return $this->transactionId;
  }
  public function setTransactionRevenue($transactionRevenue)
  {
    $this->transactionRevenue = $transactionRevenue;
  }
  public function getTransactionRevenue()
  {
    return $this->transactionRevenue;
  }
  public function setTransactionShipping($transactionShipping)
  {
    $this->transactionShipping = $transactionShipping;
  }
  public function getTransactionShipping()
  {
    return $this->transactionShipping;
  }
  public function setTransactionTax($transactionTax)
  {
    $this->transactionTax = $transactionTax;
  }
  public function getTransactionTax()
  {
    return $this->transactionTax;
  }
}
