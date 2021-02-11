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

class Google_Service_AnalyticsReporting_SearchUserActivityResponse extends Google_Collection
{
  protected $collection_key = 'sessions';
  public $nextPageToken;
  public $sampleRate;
  protected $sessionsType = 'Google_Service_AnalyticsReporting_UserActivitySession';
  protected $sessionsDataType = 'array';
  public $totalRows;

  public function setNextPageToken($nextPageToken)
  {
    $this->nextPageToken = $nextPageToken;
  }
  public function getNextPageToken()
  {
    return $this->nextPageToken;
  }
  public function setSampleRate($sampleRate)
  {
    $this->sampleRate = $sampleRate;
  }
  public function getSampleRate()
  {
    return $this->sampleRate;
  }
  /**
   * @param Google_Service_AnalyticsReporting_UserActivitySession
   */
  public function setSessions($sessions)
  {
    $this->sessions = $sessions;
  }
  /**
   * @return Google_Service_AnalyticsReporting_UserActivitySession
   */
  public function getSessions()
  {
    return $this->sessions;
  }
  public function setTotalRows($totalRows)
  {
    $this->totalRows = $totalRows;
  }
  public function getTotalRows()
  {
    return $this->totalRows;
  }
}
