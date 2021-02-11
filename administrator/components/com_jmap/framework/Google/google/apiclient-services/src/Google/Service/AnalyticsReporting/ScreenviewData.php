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

class Google_Service_AnalyticsReporting_ScreenviewData extends Google_Model
{
  public $appName;
  public $mobileDeviceBranding;
  public $mobileDeviceModel;
  public $screenName;

  public function setAppName($appName)
  {
    $this->appName = $appName;
  }
  public function getAppName()
  {
    return $this->appName;
  }
  public function setMobileDeviceBranding($mobileDeviceBranding)
  {
    $this->mobileDeviceBranding = $mobileDeviceBranding;
  }
  public function getMobileDeviceBranding()
  {
    return $this->mobileDeviceBranding;
  }
  public function setMobileDeviceModel($mobileDeviceModel)
  {
    $this->mobileDeviceModel = $mobileDeviceModel;
  }
  public function getMobileDeviceModel()
  {
    return $this->mobileDeviceModel;
  }
  public function setScreenName($screenName)
  {
    $this->screenName = $screenName;
  }
  public function getScreenName()
  {
    return $this->screenName;
  }
}
