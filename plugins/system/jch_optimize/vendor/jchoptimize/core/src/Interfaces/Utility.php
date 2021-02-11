<?php

/**
 * JCH Optimize - Performs several front-end optimizations for fast downloads
 *
 * @package   jchoptimize/core
 * @author    Samuel Marshall <samuel@jch-optimize.net>
 * @copyright Copyright (c) 2020 Samuel Marshall / JCH Optimize
 * @license   GNU/GPLv3, or later. See LICENSE file
 *
 * If LICENSE file missing, see <http://www.gnu.org/licenses/>.
 */

namespace JchOptimize\Core\Interfaces;

defined('_JEXEC') or die('Restricted access');

interface Utility
{
	/**
	 *
	 * @param   string  $text
	 */
	public static function translate($text);

	/**
	 *
	 */
	public static function unixCurrentDate();

	/**
	 *
	 * @param   string  $message
	 * @param   string  $priority
	 * @param           $filename
	 */
	public static function log($message, $priority, $filename);

	/**
	 *
	 */
	public static function lnEnd();

	/**
	 *
	 */
	public static function tab();

	/**
	 *
	 * @param   string  $path
	 */
	public static function createFolder($path);

	/**
	 *
	 * @param   string  $value
	 */
	public static function encrypt($value);

	/**
	 *
	 * @param   string  $value
	 */
	public static function decrypt($value);

	/**
	 *
	 * @param   string  $value
	 * @param   string  $default
	 * @param   string  $filter
	 * @param   string  $method
	 */
	public static function get($value, $default = '', $filter = 'cmd', $method = 'request');

	/**
	 *
	 */
	public static function getLogsPath();

	/**
	 *
	 */
	public static function menuId();

	/**
	 *
	 * @param   string   $path     Path of folder to read
	 * @param   string   $filter   A regex filter for file names
	 * @param   boolean  $recurse  True to recurse into sub-folders
	 * @param   array    $exclude  An array of files to exclude
	 *
	 * @return array        Full paths of files in the folder recursively
	 */
	public static function lsFiles($path, $filter = '.', $recurse = false, $exclude = array('.svn', 'CVS', '.DS_Store', '__MACOSX'));

	/**
	 * Returns true if current user is not logged in
	 *
	 * @return boolean
	 */
	public static function isGuest();

	/**
	 *
	 * @param $headers
	 */
	public static function sendHeaders($headers);
}
