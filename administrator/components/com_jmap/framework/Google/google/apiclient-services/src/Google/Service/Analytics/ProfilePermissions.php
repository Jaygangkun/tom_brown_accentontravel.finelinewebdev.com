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

class Google_Service_Analytics_ProfilePermissions extends Google_Collection
{
  protected $collection_key = 'effective';
  public $effective;

  public function setEffective($effective)
  {
    $this->effective = $effective;
  }
  public function getEffective()
  {
    return $this->effective;
  }
}
