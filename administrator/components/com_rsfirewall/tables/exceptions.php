<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2020 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RsfirewallTableExceptions extends JTable
{
	/**
	 * Primary Key
	 *
	 * @public int
	 */
	public $id;
	public $type;
	public $regex;
	public $match;
	public $php;
	public $sql;
	public $js;
	public $uploads;
	public $reason;
	public $date;
	public $published = 1;
		
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	public function __construct(& $db)
	{
		parent::__construct('#__rsfirewall_exceptions', 'id', $db);
	}
	
	public function store($updateNulls = false)
	{
		if (!$this->id)
		{
			$this->date = JFactory::getDate()->toSql();
		}
		
		return parent::store($updateNulls);
	}
}