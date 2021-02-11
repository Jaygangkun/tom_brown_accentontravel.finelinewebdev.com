<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2020 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class com_rsfirewallInstallerScript
{
	public function install($parent) {
		require_once JPATH_ADMINISTRATOR.'/components/com_rsfirewall/helpers/config.php';
		
		$user 	= JFactory::getUser();
		$config = RSFirewallConfig::getInstance();
		
		// this is the first time we've installed RSFirewall! so we need to setup an email here
		$config->set('log_emails', $user->get('email'));
	}
	
	public function uninstall($parent) {
		// get a new installer
		$plg_installer = new JInstaller();

		// get the database object
		$db 	= JFactory::getDbo();
		$query 	= $db->getQuery(true);

		$messages = array();
		
		$query->select($db->qn('extension_id'))
			  ->from($db->qn('#__extensions'))
			  ->where($db->qn('element').'='.$db->q('rsfirewall'))
			  ->where($db->qn('folder').'='.$db->q('system'))
			  ->where($db->qn('type').'='.$db->q('plugin'));
		$db->setQuery($query);
		if ($extension_id = $db->loadResult()) {
			$plg_installer->uninstall('plugin', $extension_id);
		}
		
		$query->clear();
		$query->select($db->qn('extension_id'))
			  ->from($db->qn('#__extensions'))
			  ->where($db->qn('element').'='.$db->q('rsfirewall'))
			  ->where($db->qn('folder').'='.$db->q('installer'))
			  ->where($db->qn('type').'='.$db->q('plugin'));
		$db->setQuery($query);
		if ($extension_id = $db->loadResult()) {
			$plg_installer->uninstall('plugin', $extension_id);
		}

		$query->clear();
		$query->select($db->qn('extension_id'))
			  ->from($db->qn('#__extensions'))
			  ->where($db->qn('element').'='.$db->q('mod_rsfirewall'))
			  ->where($db->qn('client_id').'='.$db->q('1'))
			  ->where($db->qn('type').'='.$db->q('module'));
		$db->setQuery($query);
		if ($extension_id = $db->loadResult()) {
			$plg_installer->uninstall('module', $extension_id);
		}
	}
	
	public function preflight($type, $parent)
	{
		$app = JFactory::getApplication();
		$jversion = new JVersion();

		$minj = '3.7.0';
		if (!$jversion->isCompatible($minj))
		{
			$app->enqueueMessage('Please upgrade to at least Joomla! ' . $minj . ' before continuing!', 'error');
			return false;
		}

        if (version_compare(PHP_VERSION, '5.4.0', '<'))
        {
            $app->enqueueMessage('You have a very old PHP version; the Country Blocking feature will not work since it requires a newer version. Please ask your hosting provider to upgrade to a newer version of PHP.', 'warning');
        }
		
		return true;
	}
	
	public function postflight($type, $parent) {
		if ($type == 'uninstall') {
			return true;
		}
		
		require_once JPATH_ADMINISTRATOR.'/components/com_rsfirewall/helpers/config.php';
		
		$source = $parent->getParent()->getPath('source');
		
		// Get a new installer
		$installer = new JInstaller();
		
		$messages = array(
			'plg_rsfirewall' => false,
			'plg_installer'  => false,
			'mod_rsfirewall' => false
		);
		
		$db = JFactory::getDbo();

		if ($installer->install($source.'/other/plg_rsfirewall')) {
			$query = $db->getQuery(true);
			$query->update('#__extensions')
				  ->set($db->qn('enabled').'='.$db->q(1))
				  ->set($db->qn('ordering').'='.$db->q('-999'))
				  ->where($db->qn('element').'='.$db->q('rsfirewall'))
				  ->where($db->qn('type').'='.$db->q('plugin'))
				  ->where($db->qn('folder').'='.$db->q('system'));
			$db->setQuery($query);
			$db->execute();
			
			$messages['plg_rsfirewall'] = true;
		}
		
		// Get a new installer
		$installer = new JInstaller();
		
		if ($installer->install($source.'/other/plg_installer')) {
			$query = $db->getQuery(true);
			$query->update('#__extensions')
				  ->set($db->qn('enabled').'='.$db->q(1))
				  ->where($db->qn('element').'='.$db->q('rsfirewall'))
				  ->where($db->qn('type').'='.$db->q('plugin'))
				  ->where($db->qn('folder').'='.$db->q('installer'));
			$db->setQuery($query);
			$db->execute();
			
			$messages['plg_installer'] = true;
		}
		
		if ($installer->install($source.'/other/mod_rsfirewall')) {
			$query = $db->getQuery(true);
			$query->select('id')
				  ->from('#__modules')
				  ->where($db->qn('module').'='.$db->q('mod_rsfirewall'))
				  ->where($db->qn('client_id').'='.$db->q(1))
				  ->where($db->qn('position').'='.$db->q(''));
			$db->setQuery($query);
			if ($moduleid = $db->loadResult()) {
				$query->clear();
				$query->update('#__modules')
					  ->set($db->qn('published').'='.$db->q(1))
					  ->set($db->qn('position').'='.$db->q('cpanel'))
					  ->set($db->qn('ordering').'='.$db->q(1))
					  ->where($db->qn('id').'='.$db->q($moduleid));
				$db->setQuery($query);
				$db->execute();
				
				$query->clear();
				$query->insert('#__modules_menu')
					  ->columns(array('moduleid', 'menuid'))
					  ->values("$moduleid, 0");
				$db->setQuery($query);
				$db->execute();
			}
			
			$messages['mod_rsfirewall'] = true;
		}
		
		// show message
		$this->showInstallMessage($messages);
		
		if ($type != 'update') {
			$this->removeSignatures();
			return true;
		}
		
		// update the configuration for R42
		// do we have a #__rsfirewall_lists table?
		$tables = $db->getTableList();
		if (!in_array($db->getPrefix().'rsfirewall_lists', $tables)) {
			$this->runSQL($source, 'lists.sql');
		}
		$query = $db->getQuery(true);
		$query->select('*')
			  ->from('#__rsfirewall_configuration')
			  ->where($db->qn('name').' IN ('.$this->quoteImplode(array('blacklist_ips', 'backend_whitelist_ips')).')');
		$db->setQuery($query);
		if ($old_values = $db->loadObjectList()) {
			JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_rsfirewall/tables');
			$date  = JFactory::getDate()->toSql();
			foreach ($old_values as $old_value) {
				$type = $old_value->name == 'blacklist_ips' ? 0 : 1;				
				$ips  = $this->explode($old_value->value);
				
				foreach ($ips as $ip) {
					$row = JTable::getInstance('Lists', 'RsfirewallTable');
					$row->bind(array(
						'ip' => $ip,
						'type' => $type,
						'reason' => 'Imported during R42 update.',
						'date' => $date,
						'published' => 1
					));
					$row->store();
				}
			}
			
			$query->clear();
			$query->delete('#__rsfirewall_configuration')
				  ->where($db->qn('name').' IN ('.$this->quoteImplode(array('blacklist_ips', 'backend_whitelist_ips')).')');
			$db->setQuery($query);
			$db->execute();
		}
		
		// change date
		$columns = $db->getTableColumns('#__rsfirewall_logs');
		if ($columns['date'] == 'int') {
			$db->setQuery("ALTER TABLE ".$db->qn('#__rsfirewall_logs')." CHANGE ".$db->qn('date')." ".$db->qn('date')." VARCHAR(255) NOT NULL");
			$db->execute();
			
			// convert the date
			$query = $db->getQuery(true);
			$query->update('#__rsfirewall_logs')
				  ->set($db->qn('date')."=FROM_UNIXTIME(".$db->qn('date').")");
			$db->setQuery($query);
			$db->execute();
			
			// change the column type
			$db->setQuery("ALTER TABLE ".$db->qn('#__rsfirewall_logs')." CHANGE ".$db->qn('date')." ".$db->qn('date')." DATETIME NOT NULL");
			$db->execute();
		}
		
		// userid changed to user_id
		if (isset($columns['userid'])) {
			$db->setQuery("ALTER TABLE ".$db->qn('#__rsfirewall_logs')." CHANGE ".$db->qn('userid')." ".$db->qn('user_id')." INT(11) NOT NULL");
			$db->execute();
		}
		// add referer column
		if (!isset($columns['referer'])) {
			$db->setQuery("ALTER TABLE ".$db->qn('#__rsfirewall_logs')." ADD ".$db->qn('referer')." TEXT NOT NULL AFTER ".$db->qn('page'));
			$db->execute();
		}
		
		// change type column
		$columns = $db->getTableColumns('#__rsfirewall_snapshots');
		if (strpos($columns['type'], 'enum') !== false) {
			$db->setQuery("ALTER TABLE ".$db->qn('#__rsfirewall_snapshots')." CHANGE ".$db->qn('type')." ".$db->qn('type')." VARCHAR(16) NOT NULL");
			$db->execute();
		}
		
		// change date
		$columns = $db->getTableColumns('#__rsfirewall_hashes');
		if ($columns['date'] == 'int') {
			$db->setQuery("ALTER TABLE ".$db->qn('#__rsfirewall_hashes')." CHANGE ".$db->qn('date')." ".$db->qn('date')." VARCHAR(255) NOT NULL");
			$db->execute();
			
			// convert the date
			$query = $db->getQuery(true);
			$query->update('#__rsfirewall_hashes')
				  ->set($db->qn('date')."=FROM_UNIXTIME(".$db->qn('date').")");
			$db->setQuery($query);
			$db->execute();
			
			// change the column type
			$db->setQuery("ALTER TABLE ".$db->qn('#__rsfirewall_hashes')." CHANGE ".$db->qn('date')." ".$db->qn('date')." DATETIME NOT NULL");
			$db->execute();
		}
		
		$columns = $db->getTableColumns('#__rsfirewall_configuration');
		if (!isset($columns['type'])) {
			$db->setQuery("ALTER TABLE ".$db->qn('#__rsfirewall_configuration')." ADD ".$db->qn('type')." VARCHAR(16) NOT NULL");
			$db->execute();
			
			// global_register_code has changed to code
			$query = $db->getQuery(true);
			$query->select($db->qn('name'))
				  ->from('#__rsfirewall_configuration')
				  ->where($db->qn('name').'='.$db->q('global_register_code'));
			$db->setQuery($query);
			if ($db->loadResult()) {
				$query->clear();
				$query->update('#__rsfirewall_configuration')
					  ->set($db->qn('name').'='.$db->q('code'))
					  ->set($db->qn('type').'='.$db->q('text'))
					  ->where($db->qn('name').'='.$db->q('global_register_code'));
				$db->setQuery($query);
				$db->execute();
			}
			
			$query = $db->getQuery(true);
			$types = array(
				'active_scanner_status' => 'int',
				'blocked_countries' => 'array-text',
				'backend_password_enabled' => 'int',
				'backend_password' => 'text',
				'offset' => 'int',
				'verify_generator' => 'int',
				'verify_emails' => 'int',
				'monitor_core' => 'int',
				'monitor_files' => 'text',
				'verify_agents' => 'array-text',
				'verify_dos' => 'array-text',
				'enable_autoban' => 'int',
				'enable_autoban_login' => 'int',
				'autoban_attempts' => 'int',
				'enable_backend_captcha' => 'int',
				'verify_multiple_exts' => 'int',
				'verify_upload' => 'int',
				'verify_upload_blacklist_exts' => 'text',
				'monitor_users' => 'array-int',
				'log_emails' => 'text',
				'log_alert_level' => 'array-text',
				'log_history' => 'int',
				'log_overview' => 'int',
				'log_hour_limit' => 'int',
				'log_emails_count' => 'int',
				'log_emails_send_after' => 'int',
				'code' => 'text',
				'grade' => 'int'
			);
			foreach ($types as $field => $type) {
				$query->update('#__rsfirewall_configuration')
					  ->set($db->qn('type').'='.$db->q($type))
					  ->where($db->qn('name').'='.$db->q($field));
				$db->setQuery($query);
				$db->execute();
				
				$query->clear();
			}
		}
		
		// add the missing config data
		$this->runSQL($source, 'configuration.data.sql');
		
		// these are no longer needed
		$query = $db->getQuery(true);
		$query->select($db->qn('name'))
			  ->from('#__rsfirewall_configuration')
			  ->where($db->qn('name').'='.$db->q('master_password'));
		$db->setQuery($query);
		if ($db->loadResult()) {
			$query->clear();
			
			$query->delete('#__rsfirewall_configuration')
				  ->where($db->qn('name').' IN ('.$this->quoteImplode(array('master_password', 'master_password_enabled', 'backend_access_control_enabled', 'backend_access_users', 'backend_access_components')).')');
			$db->setQuery($query);
			$db->execute();
		}
		
		$query = $db->getQuery(true);
		$query->select($db->qn('name'))
			  ->from('#__rsfirewall_configuration')
			  ->where($db->qn('name').'='.$db->q('verify_sql_skip'));
		$db->setQuery($query);
		if ($db->loadResult()) {
			$fields = array('verify_sql', 'verify_sql_skip', 'verify_js', 'verify_js_skip', 'verify_php', 'verify_php_skip', 'verify_upload_skip');
			$query = $db->getQuery(true);
			$query->select('*')
				  ->from('#__rsfirewall_configuration')
				  ->where($db->qn('name').' IN ('.$this->quoteImplode($fields).')');
			$db->setQuery($query);
			if ($results = $db->loadObjectList('name')) {
				$config = RSFirewallConfig::getInstance();
				$config->reload();
				
				// add the exceptions table
				$this->runSQL($source, 'exceptions.sql');
				
				// verify_sql
				if ($results['verify_sql']->value) {
					$config->set('enable_sql_for', array('get'));
				}
				// verify_js
				if ($results['verify_js']->value) {
					$config->set('enable_js_for', array('get', 'post'));
					$config->set('filter_js', 1);
				}
				// verify_php
				if ($results['verify_php']->value) {
					$config->set('enable_php_for', array('get'));
					$config->set('lfi', 1);
					$config->set('rfi', 1);
				}
				
				$options = array();
				if ($results['verify_sql_skip']->value) {
					$tmp = $this->explode($results['verify_sql_skip']->value);
					foreach ($tmp as $v) {
						$options[$v]['sql'] = 1;
					}
				}
				if ($results['verify_js_skip']->value) {
					$tmp = $this->explode($results['verify_js_skip']->value);
					foreach ($tmp as $v) {
						$options[$v]['js'] = 1;
					}
				}
				if ($results['verify_php_skip']->value) {
					$tmp = $this->explode($results['verify_php_skip']->value);
					foreach ($tmp as $v) {
						$options[$v]['php'] = 1;
					}
				}
				if ($results['verify_upload_skip']->value) {
					$tmp = $this->explode($results['verify_upload_skip']->value);
					foreach ($tmp as $v) {
						$options[$v]['uploads'] = 1;
					}
				}
				if ($options) {
					JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_rsfirewall/tables');
					foreach ($options as $option => $v) {
						$row = JTable::getInstance('Exceptions', 'RsfirewallTable');
						$row->bind(array(
							'type' => 'com',
							'regex' => 0,
							'match' => $option,
							'php' => isset($v['php']) ? 1 : 0,
							'sql' => isset($v['sql']) ? 1 : 0,
							'js' => isset($v['js']) ? 1 : 0,
							'uploads' => isset($v['uploads']) ? 1 : 0,
							'reason' => 'Imported from RSFirewall! update to R45'
						));
						$row->store();
					}
				}
				
				// delete them
				$query = $db->getQuery(true);
				$query->delete('#__rsfirewall_configuration')
					  ->where($db->qn('name').' IN ('.$this->quoteImplode($fields).')');
				$db->setQuery($query);
				$db->execute();
			}
		}
		// Some dot files need to be hardcoded
		$config = RSFirewallConfig::getInstance();
		$dot_files = $config->get('dot_files', array(), true);
		$dot_files = array_filter($dot_files);
		$save = false;
		foreach (array('.htaccess', '.htpasswd', '.htusers', '.htgroups') as $file)
		{
			$pos = array_search($file, $dot_files);
			if ($pos !== false)
			{
				unset($dot_files[$pos]);

				$save = true;
			}
		}
		if ($save)
		{
			$config->set('dot_files', $dot_files);
		}
		
		// lockdown has changed into disable_installer & disable_new_admin_users
		$query = $db->getQuery(true);
		$query->select('*')
			  ->from('#__rsfirewall_configuration')
			  ->where($db->qn('name').'='.$db->q('lockdown'));
		$db->setQuery($query);
		if ($lockdown = $db->loadObject()) {
			$config = RSFirewallConfig::getInstance();
				
			$config->set('disable_installer', $lockdown->value);
			$config->set('disable_new_admin_users', $lockdown->value);
			
			$query = $db->getQuery(true);
			$query->delete('#__rsfirewall_configuration')
				  ->where($db->qn('name').'='.$db->q('lockdown'));
			$db->setQuery($query);
			$db->execute();
			
			$query->clear();
			$query->delete('#__rsfirewall_snapshots')
				  ->where($db->qn('type').'='.$db->q('lockdown'));
			$db->setQuery($query);
			$db->execute();
		}
		
		// ignore files and folders
		$query = $db->getQuery(true);
		$query->select('*')
			  ->from('#__rsfirewall_ignored')
			  ->where($db->qn('type').'='.$db->q('ignore_files_folders'));
		$db->setQuery($query);
		if ($results = $db->loadObjectList()) {
			$query->clear();
			foreach ($results as $result) {
				if (is_file($result->path)) {
					$result->type = 'ignore_file';
				} elseif (is_dir($result->path)) {
					$result->type = 'ignore_folder';
				}
				
				$query->update('#__rsfirewall_ignored')
					  ->set($db->qn('type').'='.$db->q($result->type))
					  ->where($db->qn('path').'='.$db->q($result->path));
				$query->execute();
				$query->clear();
			}
		}
		
		// remove patterns, add signatures
		if (in_array($db->getPrefix().'rsfirewall_patterns', $tables)) {
			$db->dropTable('#__rsfirewall_patterns');
			
			$this->runSQL($source, 'signatures.sql');
		}
		
		// remove monitor_files
		$query = $db->getQuery(true);
		$query->select('*')
			  ->from('#__rsfirewall_configuration')
			  ->where($db->qn('name').'='.$db->q('monitor_files'));
		$db->setQuery($query);
		if ($result = $db->loadObject()) {
			$query = $db->getQuery(true);
			$query->delete('#__rsfirewall_configuration')
				  ->where($db->qn('name').'='.$db->q('monitor_files'));
			$db->setQuery($query);
			$db->execute();
			
			// save new values
			$values = $this->explode($result->value);
			foreach ($values as $value) {
				$value = trim($value);
				if (!file_exists($value) || !is_readable($value)) {
					continue;
				}
				
				JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_rsfirewall/tables');
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
		
		// admin_users should not be empty...
		require_once JPATH_ADMINISTRATOR.'/components/com_rsfirewall/helpers/users.php';
		// get the current admin users
		$users = RSFirewallUsersHelper::getAdminUsers();
		$admin_users = array();
		foreach ($users as $user) {
			$admin_users[] = $user->id;
		}
		
		$config = RSFirewallConfig::getInstance();
		$config->set('admin_users', $admin_users);
		
		// 2.7.0 update
		
		// lists
		$columns = $db->getTableColumns('#__rsfirewall_lists', false);
		if ($columns['ip']->Key == 'UNI') {
			$db->setQuery('ALTER TABLE #__rsfirewall_lists DROP INDEX ip');
			$db->execute();
		}
		
		if ($columns['ip']->Type != 'varchar(255)') {
			$db->setQuery('ALTER TABLE #__rsfirewall_lists CHANGE `ip` `ip` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ');
			$db->execute();
		}

		if ($columns['reason']->Null === 'NO')
		{
			$db->setQuery("ALTER TABLE `#__rsfirewall_lists` CHANGE `reason` `reason` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL");
			$db->execute();
		}
		
		// logs
		$columns = $db->getTableColumns('#__rsfirewall_logs', false);
		if ($columns['ip']->Type != 'varchar(255)') {
			$db->setQuery('ALTER TABLE #__rsfirewall_logs CHANGE `ip` `ip` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL');
			$db->execute();
		}
		
		if (!$columns['ip']->Key) {
			$db->setQuery('ALTER TABLE #__rsfirewall_logs ADD INDEX(`ip`); ');
			$db->execute();
			$db->setQuery('ALTER TABLE #__rsfirewall_lists ADD INDEX(`ip`); ');
			$db->execute();
		}

		if ($columns['username']->Null === 'NO')
		{
			$db->setQuery("ALTER TABLE `#__rsfirewall_logs` CHANGE `username` `username` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL");
			$db->execute();
		}

		if ($columns['page']->Null === 'NO')
		{
			$db->setQuery("ALTER TABLE `#__rsfirewall_logs` CHANGE `page` `page` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL");
			$db->execute();
		}

		if ($columns['referer']->Null === 'NO')
		{
			$db->setQuery("ALTER TABLE `#__rsfirewall_logs` CHANGE `referer` `referer` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL");
			$db->execute();
		}

		if ($columns['referer']->Null === 'NO')
		{
			$db->setQuery("ALTER TABLE `#__rsfirewall_logs` CHANGE `debug_variables` `debug_variables` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL");
			$db->execute();
		}
		
		// offenders
		$columns = $db->getTableColumns('#__rsfirewall_offenders', false);
		if ($columns['ip']->Type != 'varchar(255)') {
			$db->setQuery('ALTER TABLE #__rsfirewall_offenders CHANGE `ip` `ip` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL');
			$db->execute();
		}

		// exceptions
		$columns = $db->getTableColumns('#__rsfirewall_exceptions', false);
		if ($columns['reason']->Null === 'NO')
		{
			$db->setQuery("ALTER TABLE `#__rsfirewall_exceptions` CHANGE `reason` `reason` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL");
			$db->execute();
		}

		// hashes
		$columns = $db->getTableColumns('#__rsfirewall_hashes', false);
		if ($columns['flag']->Null === 'NO')
		{
			$db->setQuery("ALTER TABLE `#__rsfirewall_hashes` CHANGE `flag` `flag` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL");
			$db->execute();
		}

		if ($columns['date']->Null === 'NO')
		{
			$db->setQuery("ALTER TABLE `#__rsfirewall_hashes` CHANGE `date` `date` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL");
			$db->execute();
		}

		// add hashes
		$this->runSQL($source, 'hashes.data.sql');

		// remove duplicates
		$query = $db->getQuery(true);
		$query->select('COUNT(' . $db->qn('type')  . ') AS ' . $db->qn('found'))
			->select($db->qn('file'))
			->select($db->qn('type'))
			->from($db->qn('#__rsfirewall_hashes'))
			->where($db->qn('type') . ' LIKE ' . $db->q('%.%.%'))
			->group($db->qn('type'))
			->group($db->qn('file'))
			->having($db->qn('found') . ' > 1');

		if ($results = $db->setQuery($query)->loadObjectList())
		{
			foreach ($results as $result)
			{
				$query = $db->getQuery(true);
				$query->delete('#__rsfirewall_hashes')
					->where($db->qn('file') . ' = ' . $db->q($result->file))
					->where($db->qn('type') . ' = ' . $db->q($result->type))
					->order($db->qn('id') . ' ' . $db->escape('ASC'))
					->setLimit($result->found - 1);

				$db->setQuery($query)->execute();
			}
		}

		// Remove old versions
		list($major, $minor, $patch) = explode('.', JVERSION, 3);
		if (strpos($patch, '-') !== false)
		{
			$tmp = explode('-', $patch, 2);
			$patch = reset($tmp);
		}

		$query = $db->getQuery(true)
			->delete($db->qn('#__rsfirewall_hashes'))
			->where($db->qn('type') . ' LIKE ' . $db->q('%.%.%'))
			->where("CONCAT(
			LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX(" . $db->qn('type') . ", '.', 1), '.', -1), 2, '0'),
			LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX(" . $db->qn('type') . ", '.', 2), '.', -1), 2, '0'),
			LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX(" . $db->qn('type') . ", '.', 3), '.', -1), 2, '0')
		) < CONCAT(LPAD(" . $db->q($major) . ", 2, '0'), LPAD(" . $db->q($minor) . ", 2, '0'), LPAD(" . $db->q($patch) . ", 2, '0'))");
		$db->setQuery($query)->execute();

		// add signatures
		$this->runSQL($source, 'signatures.data.sql');
		
		$this->removeSignatures();
		
		// Remove GeoLite2 CABundle.php file
		$caBundle = JPATH_ADMINISTRATOR. '/components/com_rsfirewall/helpers/geolite2/vendor/composer/ca-bundle/src/CaBundle.php';
        if (file_exists($caBundle))
        {
            JFile::delete($caBundle);
        }
	}
	
	protected function removeSignatures()
	{
		// There you go hosting providers, scan this.
		jimport('joomla.filesystem.file');

		$files = array(
			JPATH_ADMINISTRATOR.'/components/com_rsfirewall/sql/mysql/signatures.data.sql'
		);

		foreach ($files as $file)
		{
			if (file_exists($file))
			{
				JFile::delete($file);
			}
		}
	}
	
	protected function runSQL($source, $file)
	{
		$db 	= JFactory::getDbo();
		$driver = 'mysql';
		
		$sqlfile = $source . '/admin/sql/' . $driver . '/' . $file;
		
		if (file_exists($sqlfile))
		{
			$buffer = file_get_contents($sqlfile);
			if ($buffer !== false)
			{
				if ($queries = $db->splitSql($buffer))
				{
					foreach ($queries as $query)
					{
						$db->setQuery($query)->execute();
					}
				}
			}
		}
	}
	
	protected function explode($string) {
		$string = str_replace(array("\r\n", "\r"), "\n", $string);
		return explode("\n", $string);
	}
	
	protected function quoteImplode($array) {
		$db = JFactory::getDbo();
		foreach ($array as $k => $v) {
			$array[$k] = $db->q($v);
		}
		
		return implode(',', $array);
	}
	
	protected function decompress($tgzFilePath) {
        // Open .tgz file for reading
        $gzHandle = @gzopen($tgzFilePath, 'rb');
        if (!$gzHandle)
        {
            throw new Exception(JText::sprintf('Could not open %s for reading!', $tgzFilePath));
        }

        $path = dirname($tgzFilePath);

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
	
	protected function showInstallMessage($messages=array()) {
?>
<style type="text/css">
.version-history {
	margin: 0 0 2em 0;
	padding: 0;
	list-style-type: none;
}
.version-history > li {
	margin: 0 0 0.5em 0;
	padding: 0 0 0 4em;
}

.version,
.version-new,
.version-fixed,
.version-upgraded {
	float: left;
	font-size: 0.8em;
	margin-left: -4.9em;
	width: 4.5em;
	color: white;
	text-align: center;
	font-weight: bold;
	text-transform: uppercase;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
}

.version {
	background: #000;
}

.version-new {
	background: #7dc35b;
}
.version-fixed {
	background: #e9a130;
}
.version-upgraded {
	background: #61b3de;
}

.install-ok {
	background: #7dc35b;
	color: #fff;
	padding: 3px;
}

.install-not-ok {
	background: #E9452F;
	color: #fff;
	padding: 3px;
}
</style>
	<div>
		<!-- until Watchful fix their code this part has to be written like this; they're emulating the backend in a frontend request and template overrides don't work because 'isis' doesn't exist in the frontend, so any calls that use the /media folder will throw an error -->
		<p><img src="<?php echo JUri::root(true) . '/media/com_rsfirewall/images/rsfirewall-box.png'; ?>" alt="RSFirewall!" /></p>
			<p>System Plugin ...
				<?php if ($messages['plg_rsfirewall']) { ?>
				<strong class="install-ok">Installed</strong>
				<?php } else { ?>
				<strong class="install-not-ok">Error installing!</strong>
				<?php } ?>
			</p>
			<p>Installer Plugin ...
				<?php if ($messages['plg_installer']) { ?>
				<strong class="install-ok">Installed</strong>
				<?php } else { ?>
				<strong class="install-not-ok">Error installing!</strong>
				<?php } ?>
			</p>
			<p>RSFirewall! Control Panel Module ...
				<?php if ($messages['mod_rsfirewall']) { ?>
				<strong class="install-ok">Installed</strong>
				<?php } else { ?>
				<strong class="install-not-ok">Error installing!</strong>
				<?php } ?>
			</p>
			<h2>Changelog v3.0.2</h2>
			<ul class="version-history">
				<li><span class="version-upgraded">Upg</span> Replaced references to lists as 'Blocklist' and 'Safelist'.</li>
				<li><span class="version-upgraded">Upg</span> The System Check can now be run with Xdebug enabled by adjusting the xdebug.max_nesting_level directive.</li>
				<li><span class="version-fixed">Fix</span> Removed some 'Ignored Hidden Files' because some hosting providers block requests containing those names; these have been instead hardcoded in the System Check process.</li>
			</ul>
			<p>
				<a class="btn btn-primary btn-large btn-lg" href="index.php?option=com_rsfirewall">Start using RSFirewall!</a>
				<a class="btn btn-secondary" href="https://www.rsjoomla.com/support/documentation/rsfirewall-user-guide.html" target="_blank">Read the RSFirewall! User Guide</a>
				<a class="btn btn-secondary" href="https://www.rsjoomla.com/support.html" target="_blank">Get Support!</a>
			</p>
	</div>
		<?php
	}
}