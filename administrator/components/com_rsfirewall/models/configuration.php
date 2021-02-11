<?php
/**
 * @package        RSFirewall!
 * @copyright  (c) 2009 - 2020 RSJoomla!
 * @link           https://www.rsjoomla.com
 * @license        GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RsfirewallModelConfiguration extends JModelAdmin
{
	protected $geoip;

	public function __construct($config = array())
	{
		$this->geoip = (object) array(
			'path'       => JPATH_COMPONENT . '/assets/geoip/',
			'filename'   => 'GeoLite2-Country.mmdb'
		);

		parent::__construct($config);
	}

	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_rsfirewall.configuration', 'configuration', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	protected function loadFormData()
	{
		$data = (array) $this->getConfig()->getData();

		if (!empty($data['backend_password']))
		{
			$data['backend_password'] = '';
		}

		return $data;
	}

	/* GeoIP functions */
	public function getGeoIPInfo()
	{
	    // Defaults
		$result = array(
			'works'		            => true,
			'mmdb'		            => false,
            'php_version_compat'    => true
		);
		
		$now = JFactory::getDate('now')->toUnix();
		
		// Check for GeoIP server files
		if (!$result['mmdb']) {
			$result['mmdb'] = file_exists($this->getGeoIPPath().$this->getGeoIPFileName());
			
			// Check file timestamp
			if ($result['mmdb']) {
				$mtime					 = filemtime($this->getGeoIPPath().$this->getGeoIPFileName());
				$result['mmdb_old'] 	 = $now - $mtime > 86400 * 30;
				$result['mmdb_modified'] = JHtml::_('date.relative', JFactory::getDate($mtime)->toSql());
			}
		}
		
		// See if GeoIP is setup correctly and works - IPv4 is the requirement
		if (!$result['mmdb']) {
			$result['works'] = false;
		}

        if (version_compare(PHP_VERSION, '5.4.0', '<')) {
            $result['php_version_compat'] = false;
        }
		
		return (object) $result;
	}

	public function getGeoIPPath()
	{
		return $this->geoip->path;
	}

	public function getGeoIPFileName()
	{
        return $this->geoip->filename;
	}

	public function downloadGeoIPDatabase()
	{
		$result = array(
			'success' => true,
			'message' => JText::_('COM_RSFIREWALL_GEOIP_SETUP_CORRECTLY')
		);

        $filename 	= $this->getGeoIPFileName();
        $url 		= false;
		
		try
		{
			$tmp_path = $this->getGeoIPPath();
			$tmp_name = $filename.'.gz';
			
			// Make sure tmp folder is writable
			if (!is_writable($tmp_path))
			{
				throw new Exception(JText::sprintf('COM_RSFIREWALL_GEOIP_DB_FOLDER_NOT_WRITABLE', $tmp_path));
			}

			// Check if license key is supplied
			$license_key = JFactory::getApplication()->input->getString('license_key');
			if (!$license_key)
			{
				throw new Exception(JText::_('COM_RSFIREWALL_GEOIP_DB_NO_LICENSE_KEY'));
			}

			// Save it
			RSFirewallConfig::getInstance()->set('maxmind_license_key', $license_key);

			// Craft URL
			$url = 'https://download.maxmind.com/app/geoip_download?edition_id=GeoLite2-Country&license_key=' . urlencode($license_key) . '&suffix=tar.gz';
			
			// Connect to server
			$http 		= JHttpFactory::getHttp();
			$response 	= $http->get($url, array(), 10);
			
			// Check if request is successful
			if ($response->code != 200)
			{
				if ($response->code === 401)
				{
					throw new Exception(JText::sprintf('COM_RSFIREWALL_GEOIP_DB_ERROR_RESPONSE_BODY', strip_tags(trim($response->body))));
				}

				throw new Exception(JText::sprintf('COM_RSFIREWALL_HTTP_ERROR_RESPONSE_CODE', $response->code));
			}
			
			// Write to a temporary file
			if (!file_put_contents($tmp_path.'/'.$tmp_name, $response->body))
			{
				throw new Exception(JText::sprintf('COM_RSFIREWALL_GEOIP_DB_UNABLE_TO_WRITE', $tmp_path.'/'.$tmp_name));
			}
			
			// Hand over to processGeoIPDatabase() function so we can extract the file
			$this->processGeoIPDatabase($tmp_name, $tmp_path.'/'.$tmp_name);
			
			// Remove the tmp file
			if (file_exists($tmp_path.'/'.$tmp_name))
			{
				JFile::delete($tmp_path.'/'.$tmp_name);
			}
		}
		catch (Exception $e)
		{
			$result['success'] = false;
			$result['url']     = $url;
			$result['message'] = $e->getMessage();
		}
		
		return $result;
	}
	
	protected function processGeoIPDatabase($filename, $tmp_file)
	{
		jimport('joomla.filesystem.file');
		
		// Get base path
		$path = $this->getGeoIPPath();
		
		// Check extension is .dat or .gz
		$ext = JFile::getExt($filename);

		// Not a valid extension
		if (!in_array($ext, array('mmdb', 'gz')))
		{
			throw new Exception(JText::_('COM_RSFIREWALL_GEOIP_DB_INCORRECT_FORMAT'));
		}
		
		// Remove the extension for further checking
		$filename = str_replace('.gz', '', $filename);
		
		// Directory must be writable
		if (!is_writable($path))
		{
			throw new Exception(JText::sprintf('COM_RSFIREWALL_GEOIP_DB_FOLDER_NOT_WRITABLE', $path));
		}
		
		// File already exists but isn't writable (can't overwrite)
		if (file_exists($path . $filename) && !is_writable($path . $filename))
		{
			throw new Exception(JText::sprintf('COM_RSFIREWALL_GEOIP_DB_EXISTS_NOT_WRITABLE', $path));
		}
		
		if (is_uploaded_file($tmp_file) && $ext != 'gz')
		{
			JFile::upload($tmp_file, $path . '/' . $filename, false, true);
		}
		
		if ($ext == 'gz')
		{
		    $this->decompress($tmp_file);
		}
	}

    protected function decompress($tgzFilePath)
    {
        // Open .tgz file for reading
        $gzHandle = @gzopen($tgzFilePath, 'rb');
        if (!$gzHandle)
        {
            throw new Exception(JText::sprintf('COM_RSFIREWALL_GEOIP_DB_UNABLE_TO_READ', $tgzFilePath));
        }

        // Get base path
		$path = $this->getGeoIPPath();

        jimport('joomla.filesystem.file');

        while (!gzeof($gzHandle)) {
            if ($block = gzread($gzHandle, 512)) {
                $meta['filename']  	= trim(substr($block, 0, 99));
                $meta['filesize']  	= octdec(substr($block, 124, 12));
                if ($bytes = ($meta['filesize'] % 512)) {
                    $meta['nullbytes'] = 512 - $bytes;
                } else {
                    $meta['nullbytes'] = 0;
                }

                if ($meta['filesize']) {
                    // Let's see if somebody edited the archive manually and archived a folder...
                    $meta['filename'] = str_replace('\\', '/', $meta['filename']);
                    if (strpos($meta['filename'], '/') !== false)
                    {
                        $parts = explode('/', $meta['filename']);
                        $meta['filename'] = end($parts);
                    }

                    // Make sure file does not contain invalid characters
                    if (preg_match('/[^a-z_\-\.0-9]/i', JFile::stripExt($meta['filename']))) {
                        throw new Exception('Attempted to extract a file with invalid characters in its name.');
                    }

                    $chunk	 = 1024*1024;
                    $left	 = $meta['filesize'];
                    $fHandle = @fopen($path.'/'.$meta['filename'], 'wb');

                    if (!$fHandle) {
                        throw new Exception(sprintf('Could not write data to file %s!', htmlentities($meta['filename'], ENT_COMPAT, 'utf-8')));
                    }

                    do {
                        $left = $left - $chunk;
                        if ($left < 0) {
                            $chunk = $left + $chunk;
                        }
                        $data = gzread($gzHandle, $chunk);

                        fwrite($fHandle, $data);

                    } while ($left > 0);

                    fclose($fHandle);
                }

                if ($meta['nullbytes'] > 0) {
                    gzread($gzHandle, $meta['nullbytes']);
                }
            }
        }
        gzclose($gzHandle);
    }

	public function uploadGeoIP()
	{
		// input
		$input = JFactory::getApplication()->input;
		$files = $input->files->get('jform', null, 'raw');
		
		if (!empty($files['geoip_upload'])) {
			foreach ($files['geoip_upload'] as $file) {
				if ($file['error']) {
					if ($file['error'] == UPLOAD_ERR_INI_SIZE)
					{
						$this->setError(JText::_('COM_RSFIREWALL_COULD_NOT_UPLOAD_ERR_INI_SIZE'));
					}
					elseif ($file['error'] == UPLOAD_ERR_FORM_SIZE)
					{
						$this->setError(JText::_('COM_RSFIREWALL_COULD_NOT_UPLOAD_ERR_FORM_SIZE'));
					}
					elseif ($file['error'] == UPLOAD_ERR_PARTIAL)
					{
						$this->setError(JText::_('COM_RSFIREWALL_COULD_NOT_UPLOAD_ERR_PARTIAL'));
					}
					elseif ($file['error'] == UPLOAD_ERR_NO_TMP_DIR)
					{
						$this->setError(JText::_('COM_RSFIREWALL_COULD_NOT_UPLOAD_ERR_NO_TMP_DIR'));
					}
					elseif ($file['error'] == UPLOAD_ERR_CANT_WRITE)
					{
						$this->setError(JText::_('COM_RSFIREWALL_COULD_NOT_UPLOAD_ERR_CANT_WRITE'));
					}
					elseif ($file['error'] == UPLOAD_ERR_EXTENSION)
					{
						$this->setError(JText::_('COM_RSFIREWALL_COULD_NOT_UPLOAD_ERR_EXTENSION'));
					}
					
					return false;
				} else {
					try {
						$this->processGeoIPDatabase($file['name'], $file['tmp_name']);
					} catch (Exception $e) {
						$this->setError($e->getMessage());
						
						return false;
					}
				}
			}
		}
		
		return true;
	}

	public function uploadConfiguration(&$data)
	{
		$files = JFactory::getApplication()->input->files->get('jform', null, 'raw');

		if (isset($files['configuration_upload']))
		{
			// File requested
			$file =& $files['configuration_upload'];

			// Uploaded & no error
			if ($file['tmp_name'] && !$file['error'])
			{
				jimport('joomla.filesystem.file');

				// Check extension is .json
				$ext = JFile::getExt($file['name']);

				if ($ext != 'json')
				{
					$this->setError(JText::_('COM_RSFIREWALL_CONFIGURATION_JSON_INCORRECT_FORMAT'));

					return false;
				}

				if (!is_readable($file['tmp_name']))
				{
					$this->setError(JText::sprintf('COM_RSFIREWALL_CONFIGURATION_JSON_NOT_READABLE', $file['tmp_name']));

					return false;
				}

				$contents = file_get_contents($file['tmp_name']);
				if (!$contents)
				{
					$this->setError(JText::_('COM_RSFIREWALL_CONFIGURATION_JSON_NO_CONTENTS'));

					return false;
				}

				$contents = json_decode($contents, true);
				if ($contents === null)
				{
					$this->setError(JText::_('COM_RSFIREWALL_CONFIGURATION_JSON_NOT_DECODED'));

					return false;
				}

				// Update paths
				if (isset($contents['root']))
				{
					if (!empty($contents['ignore_files_folders']))
					{
						$contents['ignore_files_folders'] = str_replace($contents['root'], JPATH_SITE, $contents['ignore_files_folders']);
					}
					if (!empty($contents['monitor_files']))
					{
						$contents['monitor_files'] = str_replace($contents['root'], JPATH_SITE, $contents['monitor_files']);
					}
				}

				// Workaround so we don't hash the new password twice
				if (isset($contents['backend_password']) && strlen($contents['backend_password']))
				{
					$contents['backend_password_hashed'] = $contents['backend_password'];
				}

				if (empty($data['configuration_update_code']))
				{
					$contents['code'] = $this->getConfig()->get('code');
				}

				// Override configuration data
				$data = $contents;

				return true;
			}
			elseif ($file['error'] != UPLOAD_ERR_NO_FILE)
			{
				if ($file['error'] == UPLOAD_ERR_INI_SIZE)
				{
					$this->setError(JText::_('COM_RSFIREWALL_COULD_NOT_UPLOAD_ERR_INI_SIZE'));
				}
				elseif ($file['error'] == UPLOAD_ERR_FORM_SIZE)
				{
					$this->setError(JText::_('COM_RSFIREWALL_COULD_NOT_UPLOAD_ERR_FORM_SIZE'));
				}
				elseif ($file['error'] == UPLOAD_ERR_PARTIAL)
				{
					$this->setError(JText::_('COM_RSFIREWALL_COULD_NOT_UPLOAD_ERR_PARTIAL'));
				}
				elseif ($file['error'] == UPLOAD_ERR_NO_TMP_DIR)
				{
					$this->setError(JText::_('COM_RSFIREWALL_COULD_NOT_UPLOAD_ERR_NO_TMP_DIR'));
				}
				elseif ($file['error'] == UPLOAD_ERR_CANT_WRITE)
				{
					$this->setError(JText::_('COM_RSFIREWALL_COULD_NOT_UPLOAD_ERR_CANT_WRITE'));
				}
				elseif ($file['error'] == UPLOAD_ERR_EXTENSION)
				{
					$this->setError(JText::_('COM_RSFIREWALL_COULD_NOT_UPLOAD_ERR_EXTENSION'));
				}

				return false;
			}
		}

		return true;
	}

	public function save($data)
	{
		// Upload GeoIP only if it's not built-in
		if (!$this->uploadGeoIP())
		{
			return false;
		}

		if (!$this->uploadConfiguration($data))
		{
			return false;
		}

		$db = $this->getDbo();

		// get configuration
		$config = $this->getConfig();
		// get configuration keys
		$keys = $config->getKeys();

		// update keys
		foreach ($keys as $key)
		{
			$value = '';
			if (isset($data[$key]))
			{
				$value = $data[$key];
			}

			// Ignore files and folders
			if ($key == 'ignore_files_folders')
			{
				// cleanup the table
				$query = $db->getQuery(true);
				$query->delete('#__rsfirewall_ignored')
					->where($db->qn('type') . '=' . $db->q('ignore_folder') . ' OR ' . $db->qn('type') . '=' . $db->q('ignore_file'));
				$db->setQuery($query);
				$db->execute();

				// save new values
				$values = $this->explode($value);
				foreach ($values as $value)
				{
					$config->append($key, $value);
				}

				// no need to save this in the config
				continue;
			}
			// Protect users
			elseif ($key == 'monitor_users')
			{
				if ($config->get('monitor_users') != $value)
				{
					// cleanup the table
					$query = $db->getQuery(true);
					$query->delete('#__rsfirewall_snapshots')
						->where($db->qn('type') . '=' . $db->q('protect'));
					$db->setQuery($query);
					$db->execute();

					require_once JPATH_COMPONENT . '/helpers/snapshot.php';

					if (is_array($value))
					{
						foreach ($value as $user_id)
						{
							$user_id = (int) $user_id;
							// get the user from the database - this way we avoid the JUser error showing up if the user doesn't exist
							$query 	= $db->getQuery(true);
							$query->select('*')
								->from($db->qn('#__users'))
								->where($db->qn('id').' = '.$db->q($user_id));
							$user = $db->setQuery($query)->loadObject();

							// Don't save users that cannot be loaded
							if ($user && strlen($user->username))
							{
								$table = JTable::getInstance('Snapshots', 'RsfirewallTable');
								$table->bind(array(
									'user_id'  => $user_id,
									'snapshot' => RSFirewallSnapshot::create($user),
									'type'     => 'protect'
								));
								$table->store();
							}
						}
					}
				}
			}
			// Monitor files
			elseif ($key == 'monitor_files')
			{
				if ($config->get('monitor_files') != $value)
				{
					// cleanup the table
					$query = $db->getQuery(true);
					$query->delete('#__rsfirewall_hashes')
						->where($db->qn('type') . '=' . $db->q('protect'));
					$db->setQuery($query);
					$db->execute();

					// save new values
					$values = $this->explode($value);
					foreach ($values as $value)
					{
						$value = trim($value);
						if (!file_exists($value) || !is_readable($value))
						{
							continue;
						}

						$table = JTable::getInstance('Hashes', 'RsfirewallTable');
						$table->bind(array(
							'id'   => null,
							'file' => $value,
							'hash' => md5_file($value),
							'type' => 'protect',
							'flag' => '',
							'date' => JFactory::getDate()->toSql()
						));
						$table->store();
					}
				}

				continue;
			}
			// Backend password must be encrypted
			elseif ($key == 'backend_password')
			{
				// if we have a value...
				if (!empty($data['backend_password_hashed']) && strlen($data['backend_password_hashed']) == 32)
				{
					$value = $data['backend_password_hashed'];
				}
				elseif (strlen($value))
				{
					$value = md5($value);
				}
				else
				{
					// do not save the blank password
					continue;
				}
			}
			// When we disable the creation of new admin users, we need to remember which are the default ones
			elseif ($key == 'disable_new_admin_users')
			{
				// if the value has changed, store the new admin users
				if ($config->get('disable_new_admin_users') != $value && $value == 1)
				{
					require_once JPATH_ADMINISTRATOR . '/components/com_rsfirewall/helpers/users.php';

					$users       = RSFirewallUsersHelper::getAdminUsers();
					$admin_users = array();
					foreach ($users as $user)
					{
						$admin_users[] = $user->id;
					}

					$config->set('admin_users', $admin_users);
				}
			}
			// don't update this...
			elseif ($key == 'admin_users' || $key == 'grade' || $key == 'log_emails_count' || $key == 'log_emails_send_after' || $key == 'system_check_last_run')
			{
				continue;
			}

			$config->set($key, $value);
		}

		return true;
	}

	public function toJSON()
	{
		$data = $this->getConfig()->getData();

		// Add root so we can move between servers and keep the same Ignored / Protected file paths.
		$data->root = JPATH_SITE;

		return json_encode($data);
	}

	protected function explode($string)
	{
		$string = str_replace(array("\r\n", "\r"), "\n", $string);

		return explode("\n", $string);
	}

	public function getConfig()
	{
		return RSFirewallConfig::getInstance();
	}

	public function getRSTabs()
	{
		$tabs = new RSFirewallAdapterTabs('com-rsfirewall-configuration');

		return $tabs;
	}
}