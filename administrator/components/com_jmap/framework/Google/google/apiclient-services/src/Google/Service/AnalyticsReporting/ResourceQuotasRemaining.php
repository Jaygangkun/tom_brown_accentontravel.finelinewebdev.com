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

class Google_Service_AnalyticsReporting_ResourceQuotasRemaining extends Google_Model
{
  public $dailyQuotaTokensRemaining;
  public $hourlyQuotaTokensRemaining;

  public function setDailyQuotaTokensRemaining($dailyQuotaTokensRemaining)
  {
    $this->dailyQuotaTokensRemaining = $dailyQuotaTokensRemaining;
  }
  public function getDailyQuotaTokensRemaining()
  {
    return $this->dailyQuotaTokensRemaining;
  }
  public function setHourlyQuotaTokensRemaining($hourlyQuotaTokensRemaining)
  {
    $this->hourlyQuotaTokensRemaining = $hourlyQuotaTokensRemaining;
  }
  public function getHourlyQuotaTokensRemaining()
  {
    return $this->hourlyQuotaTokensRemaining;
  }
}
