<?php
/*
 * @version 5.3.2
 * @package JotCache
 * @category Joomla 3.5
 * @copyright (C) 2010-2016 Vladimir Kanich
 * @license GNU General Public License version 2 or later
 */
defined('JPATH_BASE') or die;
jimport('joomla.plugin.plugin');
class plgSystemJotCache extends JPlugin {
protected $cache = null;
protected $exclude = false;
protected $agent = false;
protected $nocache = false;
public function __construct(& $subject, $config) {
    if (array_key_exists('HTTP_X_REQUESTED_WITH', $_SERVER)) {
return;
}    parent::__construct($subject, $config);
$app = JFactory::getApplication();
if ($app->isAdmin()) {
return;
}    $this->agent = (array_key_exists('HTTP_USER_AGENT', $_SERVER) && (strpos($_SERVER['HTTP_USER_AGENT'], 'jotcache') !== false)) ? true : false;
if ($this->params->get('errlog', '0')) {
JLog::addLogger(array('text_file' => "plg_jotcache.error.log.php", 'text_entry_format' => "{DATE} {TIME}\t{MESSAGE}"), JLog::ERROR, 'jotcache_err');
}$browser = $this->getBrowser();
if (!isset($browser)) {
$this->exclude = true;
}$globalex = $this->params->get('cacheexclude', '');
if ($globalex and $browser !== null) {
$globalex = explode(',', $globalex);
$uri = $this->getUri();
      foreach ($globalex as $ex) {
if (@strpos($uri, $ex) !== false) {
$this->exclude = true;
break;
}}}$cookieslist = $this->params->get('cachecookies', '');
$cookies = '';
if ($cookieslist !== '') {
if (substr($cookieslist, 0, 1) == '#') {
$cookieslist = substr($cookieslist, 1);
}$cookiegroups = explode('#', $cookieslist);
foreach ($cookiegroups as $cookiegroup) {
$cookiedef = trim($cookiegroup);
$cookie = $app->input->cookie->get($cookiedef, '', 'STRING');
        if ($cookie) {
$cookies .= "#" . $cookiedef . $cookie;
}}}$varlist = $this->params->get('cachesessionvars', '');
$sessionvars = '';
if ($varlist !== '') {
$app = JFactory::getApplication();
if (substr($varlist, 0, 1) == '#') {
$varlist = substr($varlist, 1);
}$vargroups = explode('#', $varlist);
foreach ($vargroups as $vargroup) {
$vardef = trim($vargroup);
$sessionvar = $app->getUserState($vardef);
if ($sessionvar) {
$sessionvars .= "#" . $vardef . $sessionvar;
}}}$options = array(
'defaultgroup' => 'page',
'lifetime' => $this->params->get('cachetime', 15) * 60,
'browsercache' => 0,
'browseron' => $this->params->get('browsercache', false),
'browsertime' => $this->params->get('browsertime', 1) * 60,
'caching' => false,
'browser' => $browser,
'cookies' => $cookies,
'sessionvars' => $sessionvars,
'uri' => $this->getUri()
);$storage = new stdClass;
$storage->type = 'file';
if (isset($this->params) && $this->params->exists('storage')) {
$storage = $this->params->get('storage', $storage);
}switch ($storage->type) {
case 'memcache':
JLoader::register('JotcacheMemcacheCache', dirname(__FILE__) . '/jotcache/JotcacheMemcacheCache.php');
$this->cache = new JotcacheMemcacheCache($options, $this->params);
break;
case 'memcached':
JLoader::register('JotcacheMemcachedCache', dirname(__FILE__) . '/jotcache/JotcacheMemcachedCache.php');
$this->cache = new JotcacheMemcachedCache($options, $this->params);
break;
default:
JLoader::register('JotcacheFileCache', dirname(__FILE__) . '/jotcache/JotcacheFileCache.php');
$this->cache = new JotcacheFileCache($options, $this->params);
break;
}}protected function getUri() {
$uri = JUri::getInstance();
if ($this->params->get('qsexclude', '0')) {
$qs = $uri->toString(array('query'));
if ($qs) {
$this->exclude = true;
return;
}if ($this->params->get('domain', '0')) {
$uri = $uri->toString(array('scheme', 'host', 'port', 'path'));
} else {
$uri = $uri->toString(array('path'));
}} else {
if ($this->params->get('domain', '0')) {
$uri = $uri->toString(array('scheme', 'host', 'port', 'path', 'query'));
} else {
$uri = $uri->toString(array('path', 'query'));
}}return $uri;
}protected function getBrowser() {
$browser = "";
$cacheClient = $this->params->get('cacheclient', '');
$botExclude = $this->params->get('botexclude', '1');
if ($cacheClient || $botExclude) {          JLoader::register('UserAgent', dirname(__FILE__) . '/jotcache/UserAgent.php');
$userAgent = new UserAgent();
$browser = $userAgent->getBrowserName();
if ($browser === null || ($botExclude && $browser == 'bot')) {
if ($this->agent !== true) {
$this->exclude = true;
}return null;
}if ($browser == 'msie') {
$browser .= str_replace('.', '', substr($userAgent->getBrowserVersion(), 0, 2));
}if (isset($cacheClient->$browser)) {
$mode = (int)$cacheClient->$browser;
} else {
return '';
}if ($mode === 0) {
if ($this->agent !== true) {
$this->exclude = true;
}return '';
}if ($mode === 1) {
return '';
}}return $browser;
}protected function jcIntegrationCheck($data) {
$jcCode = $this->params->get('jcintegration', '');
switch ($jcCode) {
case 'rok':
preg_match_all('#\/cache\/rokbooster\/[0-9a-f]*\.php#', $data, $matches);
if (!empty($matches)) {
$matchedArray = $matches[0];
foreach ($matchedArray as $file) {
$cachedFile = JPATH_ROOT . $file;
if (!file_exists($cachedFile)) {
$this->nocache = true;
return true;
}}}return false;
break;
case 'jch':
preg_match_all('#media/plg_jchoptimize\/assets\/[^\.]*\.(js|css)#', $data, $matches);
if (!empty($matches)) {
$matchedArray = $matches[0];
$siteUrl = JURI::root();
foreach ($matchedArray as $file) {
$cachedFile = $siteUrl . $file;
$back = trim(file_get_contents($cachedFile));
if ($back == 'File not found') {
return true;
}}}return false;
break;
case 'scr':
preg_match_all('#\/cache\/plg_scriptmerge\/[0-9a-f]*\.(js|css)#', $data, $matches);
if (!empty($matches)) {
$matchedArray = $matches[0];
foreach ($matchedArray as $file) {
$cachedFile = JPATH_ROOT . $file;
$cachedFileEx = JPATH_ROOT . $file . '_expire';
if (!file_exists($cachedFile) || !file_exists($cachedFileEx)) {
$this->nocache = true;
return true;
}}}return false;
break;
default:
return false;
break;
}}public function onAfterRoute() {
global $_PROFILER;
$app = JFactory::getApplication();
$user = JFactory::getUser();
$renew = ($app->input->cookie->get('jotcachemark', '0', 'INT') == 2);
if ($this->agent) {
return;
}if ($app->isAdmin() || JDEBUG || (count($app->getMessageQueue()) > 0) || $this->exclude || $renew) {
return;
}if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$path = $this->cache->_getFilePath();
$this->cache->remove($path);
}if ($this->params->get('autoclean', 0)) {
$this->cache->autoclean();
}if (!$user->get('guest') || $_SERVER['REQUEST_METHOD'] != 'GET') {
return;
}$data = $this->cache->get();
if ($this->params->get('jcintegration', '') && $this->jcIntegrationCheck($data)) {
return;
}$this->setCacheMark($app);
if ($data !== false) {
$data = $this->rewriteData($data, $app);
      if ($data === null) {
return;
}$token = JSession::getFormToken();
$search = '#<input type="hidden" name="[0-9a-f]{32}" value="1" />#';
$replacement = '<input type="hidden" name="' . $token . '" value="1" />';
$data = preg_replace($search, $replacement, $data);
$cookieMark = $app->input->cookie->get('jotcachemark', '0', 'INT');
if ($cookieMark) {
$siteUrl = JURI::root();
$lang = JFactory::getLanguage();
$lang->load('plg_system_jotcache', JPATH_ADMINISTRATOR, null, false, true);
$renewUrl = $siteUrl . 'administrator/index.php?option=com_jotcache&view=main&task=renew&token=';
$linkCss = '<link rel="stylesheet" href="' . $siteUrl . 'plugins/system/jotcache/jotcache/plg_jotcache.css?ver=5.3.2" type="text/css" />';
$data = preg_replace('#<title>(.*)<\/title>#', '<title>[MARK] ${1}</title>' . $linkCss, $data);
$data = preg_replace('#<body([^>]*)>#', '<body ${1}><div class="jotcache_top"><p>JotCache Mark Mode</p><p class="jotcache_fix"><a href="' . $renewUrl . $this->cache->fname . '">' . JText::_('JOTCACHE_RENEW_LBL') . '</a></p></div>', $data);
} else {
$data = preg_replace('#<jot .*?></jot>#', '', $data);
}$uri = $this->getUri();
if ($this->cache->options['browseron']) {
$btime = $this->getBrowserTime($uri);
if ($btime > 0) {
$app->allowCache(true);
$app->setHeader('Expires', gmdate('D, d M Y H:i:s', time() + $btime) . ' GMT');
}}if (strpos($uri, 'format=feed') !== false) {
$app->setHeader('Content-Type', 'application/rss+xml; charset=utf-8');
} else {
$app->setHeader('Content-Type', 'text/html; charset=utf-8');
}$app->setHeader('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT');
$app->setBody($data);
echo $app->toString($app->get('gzip'));
if (JDEBUG) {
$_PROFILER->mark('afterCache');
echo implode('', $_PROFILER->getBuffer());
}$app->close();
}}protected function rewriteData($data, $app) {
$document = JFactory::getDocument();
if ($document->getType() != 'html') {
return null;
}    if (strpos($data, '</jot>') !== false) {
preg_match_all('#<jot\s([_a-zA-Z0-9-]*)\s[es]\s?((?:\w*="?[_a-zA-Z0-9-\.\s]*"?\s*)*)><\/jot>#', $data, $matches);
} else {
preg_match_all('#<jot\s([_a-zA-Z0-9-]*)\s[es]\s?((?:\w*="[_a-zA-Z0-9-\.\s]*"\s*)*)>#', $data, $matches);
}    $marks = $matches[0];
$checks = array_unique($matches[1]);
$attrs = $matches[2];
$err = array();
for ($i = 0; $i < count($marks); $i = $i + 2) {
if (!empty($attrs[$i])) {
$attrs[$i] = ' ' . $attrs[$i];
}if ($marks[$i] != "<jot " . @$checks[$i] . " s" . @$attrs[$i] . "></jot>" || @$marks[$i + 1] != "<jot " . @$checks[$i] . " e></jot>")
$err[] = @$checks[$i];
}if (!array_key_exists(0, $err)) {
      $lang = JFactory::getLanguage();
      $lang->load('lib_joomla', JPATH_SITE, null, false, false)
      || $lang->load('lib_joomla', JPATH_SITE, null, true);
$template = $app->getTemplate();
$lang->load('tpl_' . $template, JPATH_BASE, null, false, false) || $lang->load('tpl_' . $template, JPATH_THEMES . "/$template", null, false, false) || $lang->load('tpl_' . $template, JPATH_BASE, $lang->getDefault(), false, false) || $lang->load('tpl_' . $template, JPATH_THEMES . "/$template", $lang->getDefault(), false, false);
$end = 0;
foreach ($checks as $key => $value) {
$start = strpos($data, "<jot " . $value . " s" . $attrs[$key] . "></jot>", $end) + strlen($value) + strlen($attrs[$key]) + 14;
$end = strpos($data, "<jot " . $value . " e></jot>", $start);
$chunk = substr($data, $start, $end - $start);
$attribs = JUtility::parseAttributes($attrs[$key]);
$attribs['name'] = $value;
        $replacement = $document->getBuffer('modules', $value, $attribs);
$cookieMark = $app->input->cookie->get('jotcachemark', '0', 'INT');
        if ($cookieMark) {
$replacement = '<div style="outline: Red dashed thin;overflow: hidden;">' . $replacement . '</div>';
}        $part1 = substr($data, 0, $start);
$part2 = substr($data, $end);
$data = $part1 . $replacement . $part2;
$end = $end - strlen($chunk) + strlen($replacement);
}} else {
if ($this->params->get('errlog', '0')) {
foreach ($err as $errItem) {
JLog::add('<jot> tags error for position : ' . $errItem, JLog::ERROR, 'jotcache_err');
}}}return $data;
}public function onAfterRender() {
if ($this->nocache) {
return;
}$app = JFactory::getApplication();
if ($app->isAdmin() || JDEBUG || $_SERVER['REQUEST_METHOD'] == 'POST' || $this->exclude) {
return;
}    if ((count($app->getMessageQueue()) > 0)) {
return;
}$user = JFactory::getUser();
    if ($this->params->get('editdelete', '0') && $user->authorise('core.create')) {
$this->cache->remove($this->cache->_getFilePath());
return;
}    if ($this->params->get('urlfilter', '1') && $this->blockedUri()) {
return;
}$mark = $this->setCacheMark($app);
if ($user->get('guest')) {
JLoader::register('JotcacheBundle', dirname(__FILE__) . '/jotcache/JotcacheBundle.php');
$bundle = new JotcacheBundle($this->params, $this->cache);
if ($bundle->checkExclude()) {
return;
}$uri = $this->getUri();
$bundle->storeBundle($app, $uri, $mark, $this->agent);
if ($this->cache->options['browseron']) {
$btime = $this->getBrowserTime($uri);
if ($btime > 0) {
$app->allowCache(true);
          $app->setHeader('Expires', gmdate('D, d M Y H:i:s', time() + $btime) . ' GMT');
        }}$data = $app->getBody();
      $this->cache->store($data);
$data = preg_replace('#<jot .*?></jot>#', '', $data);
$cookieMark = $app->input->cookie->get('jotcachemark', '0', 'INT');
      switch ($cookieMark) {
case 1:
$data = preg_replace('#<title>(.*)<\/title>#', '<title>[CACHED] \\1</title>', $data);
break;
case 2:
$data = preg_replace('#<title>(.*)<\/title>#', '<title>[RENEW] \\1</title>', $data);
break;
default:
break;
}$app->setBody($data);
    }}protected function setCacheMark($app) {
$cookieMark = $app->input->cookie->get('jotcachemark', '0', 'INT');
    if ($cookieMark) {
$database = JFactory::getDBO();
$fname = $this->cache->fname;
$query = $database->getQuery(true);
$query->update($database->quoteName('#__jotcache'))
->set('mark=1')
->where($database->quoteName('fname') . ' = ' . $database->quote($fname));
try {
$database->setQuery($query)->execute();
} catch (RuntimeException $ex) {
if ($this->params->get('errlog', '0')) {
JLog::add($ex->getMessage(), JLog::ERROR, 'jotcache_err');
}}return true;
}return false;
}public function getBrowserTime($uri) {
$db = JFactory::getDBO();
$query = $db->getQuery(true);
$query->select('value')
->from('#__jotcache_exclude')
->where($db->quoteName('type') . ' = 3');
$data = $db->setQuery($query, 0, 1)->loadResult();
$items = unserialize($data);
$btime = 0;
if (is_array($items)) {
ksort($items, SORT_STRING);
foreach ($items as $key => $value) {           if (strtolower(substr($uri, 0, strlen($key))) == strtolower($key)) {
$btime = $value;
break;
}}}return 60 * $btime;
}private function blockedUri() {
$domains = trim($this->params->get('domainfilter', ''));
if ($domains) {
$allowed_domains = explode(',', $domains);
      $info = array_key_exists('HTTP_REFERER', $_SERVER) ? $_SERVER['HTTP_REFERER'] : '';
      $ref = true;
foreach ($allowed_domains as $domain) {
$site_url = trim($domain);
$ref = substr($info, 0, strlen($site_url)) == $site_url ? false : true;
if ($ref === false) {
break;
}}if ($ref && !$this->agent) {
return true;
}}$uri = strtolower(urldecode($_SERVER['REQUEST_URI']));
$invalid = preg_match('#(mosConfig|https?|<\s*script|;|\<|\>|\"|[.][.]\/)#', $uri);
    if ($invalid) {
return true;
} else {
preg_match('#(\w*)\.php#', $uri, $matches);
if (count($matches) > 0 && $matches[1] != 'index') {
return true;
}}return false;
}}