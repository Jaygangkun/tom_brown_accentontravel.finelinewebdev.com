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

class Google_Service_Analytics_ProfileFilterLink extends Google_Model
{
  protected $filterRefType = 'Google_Service_Analytics_FilterRef';
  protected $filterRefDataType = '';
  public $id;
  public $kind;
  protected $profileRefType = 'Google_Service_Analytics_ProfileRef';
  protected $profileRefDataType = '';
  public $rank;
  public $selfLink;

  /**
   * @param Google_Service_Analytics_FilterRef
   */
  public function setFilterRef(Google_Service_Analytics_FilterRef $filterRef)
  {
    $this->filterRef = $filterRef;
  }
  /**
   * @return Google_Service_Analytics_FilterRef
   */
  public function getFilterRef()
  {
    return $this->filterRef;
  }
  public function setId($id)
  {
    $this->id = $id;
  }
  public function getId()
  {
    return $this->id;
  }
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  public function getKind()
  {
    return $this->kind;
  }
  /**
   * @param Google_Service_Analytics_ProfileRef
   */
  public function setProfileRef(Google_Service_Analytics_ProfileRef $profileRef)
  {
    $this->profileRef = $profileRef;
  }
  /**
   * @return Google_Service_Analytics_ProfileRef
   */
  public function getProfileRef()
  {
    return $this->profileRef;
  }
  public function setRank($rank)
  {
    $this->rank = $rank;
  }
  public function getRank()
  {
    return $this->rank;
  }
  public function setSelfLink($selfLink)
  {
    $this->selfLink = $selfLink;
  }
  public function getSelfLink()
  {
    return $this->selfLink;
  }
}
