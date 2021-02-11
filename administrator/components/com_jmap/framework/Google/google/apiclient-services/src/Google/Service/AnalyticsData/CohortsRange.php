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

class Google_Service_AnalyticsData_CohortsRange extends Google_Model
{
  public $endOffset;
  public $granularity;
  public $startOffset;

  public function setEndOffset($endOffset)
  {
    $this->endOffset = $endOffset;
  }
  public function getEndOffset()
  {
    return $this->endOffset;
  }
  public function setGranularity($granularity)
  {
    $this->granularity = $granularity;
  }
  public function getGranularity()
  {
    return $this->granularity;
  }
  public function setStartOffset($startOffset)
  {
    $this->startOffset = $startOffset;
  }
  public function getStartOffset()
  {
    return $this->startOffset;
  }
}
