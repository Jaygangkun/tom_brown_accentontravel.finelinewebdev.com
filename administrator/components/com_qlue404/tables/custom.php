<?php 

// no direct access
defined('_JEXEC') or die('Restricted Access');

// import the table library
jimport('joomla.database.table');
jimport('joomla.utilities.arrayhelper');

class Qlue404TableCustom extends JTable {
		
	function __construct(&$db) {
		parent::__construct('#__qlue404', 'id', $db);
	}
	
	public function bind($array, $ignore = '') {
		
		if (isset($array['params']) && is_array($array['params'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = (string)$registry;
		}
		
		if (isset($array['error']) && is_array($array['error'])) {
			JArrayHelper::toInteger($array['error']);
			$array['error'] = implode(',', $array['error']);
		}
		
		return parent::bind($array, $ignore);
	}
	
	public function load($keys = null, $operator = '=', $reset = true) {
			
		if (empty($keys)) {
			// If empty, use the value of the current key
			$keyName = $this->_tbl_key;
			$keyValue = $this->$keyName;

			// If empty primary key there's is no need to load anything
			if (empty($keyValue)) {
				return true;
			}

			$keys = array($keyName => $keyValue);
		}
		else if (!is_array($keys)) {
			// Load by primary key.
			$keys = array($this->_tbl_key => $keys);
		}
		
		// Make sure the operator is valid
		if(!in_array($operator, array('=', 'LIKE'))) {
			$operator = '=';
		}

		if ($reset) {
			$this->reset();
		}

		// Initialise the query.
		$query	= $this->_db->getQuery(true);
		$query->select('*');
		$query->from($this->_tbl);
		$fields = array_keys($this->getProperties());

		foreach ($keys as $field => $value)
		{
			// Check that $field is in the table.
			if (!in_array($field, $fields)) {
				$e = new JException(JText::sprintf('JLIB_DATABASE_ERROR_CLASS_IS_MISSING_FIELD', get_class($this), $field));
				$this->setError($e);
				return false;
			}
			// Add the search tuple to the query.
			$query->where($this->_db->quoteName($field).' '. trim($operator) .' '.$this->_db->quote($value));
		}

		$this->_db->setQuery($query);
		$row = $this->_db->loadAssoc();

		// Check for a database error.
		if ($this->_db->getErrorNum()) {
			$e = new JException($this->_db->getErrorMsg());
			$this->setError($e);
			return false;
		}

		// Check that we have a result.
		if (empty($row)) {
			$e = new JException(JText::_('JLIB_DATABASE_ERROR_EMPTY_ROW_RETURNED'));
			$this->setError($e);
			return false;
		}

		// Bind the object with the row and return.
		return $this->bind($row);
	}
	
	function check() {
		
		// Remove all HTML tags from the title
		$filter = new JFilterInput(array(), array(), 0, 0);
		$this->title = $filter->clean($this->title);
		
		// Check if title is empty
		if(trim($this->title) == '') {
			// Set the error and return false
			$this->setError(JText::_('COM_QLUE404_ERR_TITLE_REQUIRED'));
			return false;
		}
		
		// Check if description is empty
		if(trim($this->description) == '') {
			// Set the error and return false
			$this->setError(JText::_('COM_QLUE404_ERR_DESCRIPTION_REQUIRED'));
			return false;
		}
		
		// Check to see if an error has been set
		if(empty($this->error)) {
			// Set the error and return false
			$this->setError(JText::_('COM_QLUE404_ERR_ERROR_REQUIRED'));
			return false;
		}
		
		$db = JFactory::getDbo();
		$table = JTable::getInstance('Custom', 'Qlue404Table');
		
		$errors = explode(',', $this->error);

		foreach($errors as $error) {	
			if($table->load(array('error'=> '%'. (int)$error .'%'), 'LIKE') && ($table->id != $this->id)) {
				$this->setError(JText::sprintf('COM_QLUE404_ERR_ERROR_USED', JText::_('COM_QLUE404_ERROR_'. (int)$error)) );
				return false;
			}
		}
		
		return true;
	}
	
	public function store($updateNulls = false) {
		$date	= JFactory::getDate();
		if ($this->id) {
			// Existing item
			$this->modified = $date->toSql();
		} else {
			// New article. An article created and created_by field can be set by the user,
			// so we don't touch either of these if they are set.
			if (!intval($this->created)) {
				$this->created = $date->toSql();
				$this->modified = $date->toSql();
			}
		}
		return parent::store($updateNulls);
	}
}
?>