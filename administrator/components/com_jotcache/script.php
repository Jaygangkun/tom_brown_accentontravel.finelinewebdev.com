<?php
/*
 * @version 5.3.2
 * @package JotCache
 * @category Joomla 3.5
 * @copyright (C) 2010-2016 Vladimir Kanich
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
class com_jotcacheInstallerScript {
function uninstall() {
if (count(JFactory::getApplication()->getMessageQueue()) > 0) {
echo "Error condition - Uninstallation not successfull! You have to manually remove com_jotcache from '.._extensions' table as well as to drop '.._jotcache' and '.._jotcache_exclude' tables";
} else {
echo "Uninstallation successfull!";
}}}