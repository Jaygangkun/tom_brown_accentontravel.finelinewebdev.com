<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2020 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RsfirewallTableLogs extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	public $id;
	
	public $level;
	public $date;
	public $ip;
	public $user_id;
	public $username;
	public $page;
	public $referer;
	public $code;
	public $debug_variables;
		
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	public function __construct(& $db) {
		parent::__construct('#__rsfirewall_logs', 'id', $db);
	}
}