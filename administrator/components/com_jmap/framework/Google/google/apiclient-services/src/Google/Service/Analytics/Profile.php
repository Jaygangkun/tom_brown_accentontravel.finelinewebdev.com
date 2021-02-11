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

class Google_Service_Analytics_Profile extends Google_Model
{
  public $accountId;
  public $botFilteringEnabled;
  protected $childLinkType = 'Google_Service_Analytics_ProfileChildLink';
  protected $childLinkDataType = '';
  public $created;
  public $currency;
  public $defaultPage;
  public $eCommerceTracking;
  public $enhancedECommerceTracking;
  public $excludeQueryParameters;
  public $id;
  public $internalWebPropertyId;
  public $kind;
  public $name;
  protected $parentLinkType = 'Google_Service_Analytics_ProfileParentLink';
  protected $parentLinkDataType = '';
  protected $permissionsType = 'Google_Service_Analytics_ProfilePermissions';
  protected $permissionsDataType = '';
  public $selfLink;
  public $siteSearchCategoryParameters;
  public $siteSearchQueryParameters;
  public $starred;
  public $stripSiteSearchCategoryParameters;
  public $stripSiteSearchQueryParameters;
  public $timezone;
  public $type;
  public $updated;
  public $webPropertyId;
  public $websiteUrl;

  public function setAccountId($accountId)
  {
    $this->accountId = $accountId;
  }
  public function getAccountId()
  {
    return $this->accountId;
  }
  public function setBotFilteringEnabled($botFilteringEnabled)
  {
    $this->botFilteringEnabled = $botFilteringEnabled;
  }
  public function getBotFilteringEnabled()
  {
    return $this->botFilteringEnabled;
  }
  /**
   * @param Google_Service_Analytics_ProfileChildLink
   */
  public function setChildLink(Google_Service_Analytics_ProfileChildLink $childLink)
  {
    $this->childLink = $childLink;
  }
  /**
   * @return Google_Service_Analytics_ProfileChildLink
   */
  public function getChildLink()
  {
    return $this->childLink;
  }
  public function setCreated($created)
  {
    $this->created = $created;
  }
  public function getCreated()
  {
    return $this->created;
  }
  public function setCurrency($currency)
  {
    $this->currency = $currency;
  }
  public function getCurrency()
  {
    return $this->currency;
  }
  public function setDefaultPage($defaultPage)
  {
    $this->defaultPage = $defaultPage;
  }
  public function getDefaultPage()
  {
    return $this->defaultPage;
  }
  public function setECommerceTracking($eCommerceTracking)
  {
    $this->eCommerceTracking = $eCommerceTracking;
  }
  public function getECommerceTracking()
  {
    return $this->eCommerceTracking;
  }
  public function setEnhancedECommerceTracking($enhancedECommerceTracking)
  {
    $this->enhancedECommerceTracking = $enhancedECommerceTracking;
  }
  public function getEnhancedECommerceTracking()
  {
    return $this->enhancedECommerceTracking;
  }
  public function setExcludeQueryParameters($excludeQueryParameters)
  {
    $this->excludeQueryParameters = $excludeQueryParameters;
  }
  public function getExcludeQueryParameters()
  {
    return $this->excludeQueryParameters;
  }
  public function setId($id)
  {
    $this->id = $id;
  }
  public function getId()
  {
    return $this->id;
  }
  public function setInternalWebPropertyId($internalWebPropertyId)
  {
    $this->internalWebPropertyId = $internalWebPropertyId;
  }
  public function getInternalWebPropertyId()
  {
    return $this->internalWebPropertyId;
  }
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  public function getKind()
  {
    return $this->kind;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
  /**
   * @param Google_Service_Analytics_ProfileParentLink
   */
  public function setParentLink(Google_Service_Analytics_ProfileParentLink $parentLink)
  {
    $this->parentLink = $parentLink;
  }
  /**
   * @return Google_Service_Analytics_ProfileParentLink
   */
  public function getParentLink()
  {
    return $this->parentLink;
  }
  /**
   * @param Google_Service_Analytics_ProfilePermissions
   */
  public function setPermissions(Google_Service_Analytics_ProfilePermissions $permissions)
  {
    $this->permissions = $permissions;
  }
  /**
   * @return Google_Service_Analytics_ProfilePermissions
   */
  public function getPermissions()
  {
    return $this->permissions;
  }
  public function setSelfLink($selfLink)
  {
    $this->selfLink = $selfLink;
  }
  public function getSelfLink()
  {
    return $this->selfLink;
  }
  public function setSiteSearchCategoryParameters($siteSearchCategoryParameters)
  {
    $this->siteSearchCategoryParameters = $siteSearchCategoryParameters;
  }
  public function getSiteSearchCategoryParameters()
  {
    return $this->siteSearchCategoryParameters;
  }
  public function setSiteSearchQueryParameters($siteSearchQueryParameters)
  {
    $this->siteSearchQueryParameters = $siteSearchQueryParameters;
  }
  public function getSiteSearchQueryParameters()
  {
    return $this->siteSearchQueryParameters;
  }
  public function setStarred($starred)
  {
    $this->starred = $starred;
  }
  public function getStarred()
  {
    return $this->starred;
  }
  public function setStripSiteSearchCategoryParameters($stripSiteSearchCategoryParameters)
  {
    $this->stripSiteSearchCategoryParameters = $stripSiteSearchCategoryParameters;
  }
  public function getStripSiteSearchCategoryParameters()
  {
    return $this->stripSiteSearchCategoryParameters;
  }
  public function setStripSiteSearchQueryParameters($stripSiteSearchQueryParameters)
  {
    $this->stripSiteSearchQueryParameters = $stripSiteSearchQueryParameters;
  }
  public function getStripSiteSearchQueryParameters()
  {
    return $this->stripSiteSearchQueryParameters;
  }
  public function setTimezone($timezone)
  {
    $this->timezone = $timezone;
  }
  public function getTimezone()
  {
    return $this->timezone;
  }
  public function setType($type)
  {
    $this->type = $type;
  }
  public function getType()
  {
    return $this->type;
  }
  public function setUpdated($updated)
  {
    $this->updated = $updated;
  }
  public function getUpdated()
  {
    return $this->updated;
  }
  public function setWebPropertyId($webPropertyId)
  {
    $this->webPropertyId = $webPropertyId;
  }
  public function getWebPropertyId()
  {
    return $this->webPropertyId;
  }
  public function setWebsiteUrl($websiteUrl)
  {
    $this->websiteUrl = $websiteUrl;
  }
  public function getWebsiteUrl()
  {
    return $this->websiteUrl;
  }
}
