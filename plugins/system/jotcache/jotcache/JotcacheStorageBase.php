<?php
/*
 * @version 5.3.2
 * @package JotCache
 * @category Joomla 3.5
 * @copyright (C) 2010-2016 Vladimir Kanich
 * @license	GNU General Public License version 2
 */
defined('JPATH_BASE') or die;
interface JotcacheStorageBase {
  function get();
function store($data);
function remove($path);
function autoclean();
}