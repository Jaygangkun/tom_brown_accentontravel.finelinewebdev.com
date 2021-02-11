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

class Google_Service_AnalyticsReporting_Dimension extends Google_Collection
{
  protected $collection_key = 'histogramBuckets';
  public $histogramBuckets;
  public $name;

  public function setHistogramBuckets($histogramBuckets)
  {
    $this->histogramBuckets = $histogramBuckets;
  }
  public function getHistogramBuckets()
  {
    return $this->histogramBuckets;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
}
