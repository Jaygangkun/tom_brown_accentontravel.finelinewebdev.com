<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Platform" on 2019-09-01 09:26:19 */

/*
 * @package     Extly Infrastructure Support for Joomla
 *
 * @author      Extly, CB. <team@extly.com>
 * @copyright   Copyright (c)2007-2019 Extly, CB. All rights reserved.
 * @license     http://www.opensource.org/licenses/mit-license.html  MIT License
 *
 * @see         https://www.extly.com
 */

namespace XTP_BUILD\Extly\Infrastructure\Service\Cms\Joomla;

use ArrayAccess;
use XTP_BUILD\Extly\Infrastructure\Creator\CreatorTrait;
use XTP_BUILD\Extly\Infrastructure\Service\Cms\CmsException;
use XTP_BUILD\Extly\Infrastructure\Service\Cms\CmsServiceAbstract;
use XTP_BUILD\Extly\Infrastructure\Service\Cms\CmsSettingsRegistry;
use XTP_BUILD\Extly\Infrastructure\Service\Cms\Contracts\CmsServiceInterface;
use XTP_BUILD\Extly\Infrastructure\Support\Estring;
use XTP_BUILD\Extly\Infrastructure\Support\UrlHelper;
use JConfig;
use JEventDispatcher;
use JLoader;
use Joomla\CMS\Application\ApplicationHelper as CMSApplicationHelper;
use Joomla\CMS\Cache\Cache as CMSCache;
use Joomla\CMS\Cache\Exception\CacheExceptionInterface as CMSCacheException;
use Joomla\CMS\Component\ComponentHelper as CMSComponentHelper;
use Joomla\CMS\Factory as CMSFactory;
use Joomla\CMS\Input\Cli as CMSCliInput;
use Joomla\CMS\Input\Input as CMSWebInput;
use Joomla\CMS\Language\Language as CMSLanguage;
use Joomla\CMS\Language\LanguageHelper as CMSLanguageHelper;
use Joomla\CMS\Language\Text as CMSText;
use Joomla\CMS\Layout\LayoutHelper as CMSLayoutHelper;
use Joomla\CMS\Plugin\PluginHelper as CMSPluginHelper;
use Joomla\CMS\Session\Session as CMSSession;
use Joomla\CMS\Uri\Uri as CMSUri;
use Joomla\Console\Application as J4CMSConsoleApp;
use Joomla\Registry\Registry as CMSRegistry;
use Joomla\Session\Session as JoomlaSession;
use Joomla\Session\SessionInterface as CMSSessionInterface;

class JoomlaService extends CmsServiceAbstract implements CmsServiceInterface
{
    use CreatorTrait;

    protected $component;

    protected $isJ4;

    public function __construct($name, array $config = null)
    {
        parent::__construct($name, $config);

        // The minimum configuration is empty, we can't load the CMS
        if (empty($this->config[CmsSettingsRegistry::CONFIG_CMS_PATH_ROOT])) {
            $this->config[CmsSettingsRegistry::CONFIG_CMS_PATH_ROOT] = $this->detectCmsPathRoot();
        }

        // Nothing else to do
        if (empty($this->config[CmsSettingsRegistry::CONFIG_CMS_PATH_ROOT])) {
            throw new CmsException('Minimum configuration (CMS_PATH_ROOT) for Joomla has not been provided.');
        }

        defined('JOOMLA_SITE_PATH') || define('JOOMLA_SITE_PATH', $this->config[CmsSettingsRegistry::CONFIG_CMS_PATH_ROOT]);
        defined('JOOMLA_SITE_INCLUDES_PATH') || define('JOOMLA_SITE_INCLUDES_PATH', JOOMLA_SITE_PATH.'/includes');

        if (!class_exists('JConfig')) {
            $this->createCms();
        }

        $extensionAlias = $this->config[CmsSettingsRegistry::CONFIG_EXTENSION_ALIAS];
        $this->defineComponent('com_'.$extensionAlias);

        if ($this->isCli()) {
            $this->setServerHttpHost($this->getRootUri());
        }

        // TO-DO: Define Joomla timezone
    }

    /**
     * detectCmsPathRoot.
     *
     * @return string
     */
    public function detectCmsPathRoot()
    {
        // www/libraries/xtplatform/vendor/anibalsanchez/extly-infrastructure-cms-joomla/src/Infrastructure/Service/Cms/Joomla/
        $pathRoot = realpath(__DIR__.'/../../../../../../../../../..');

        if (($pathRoot) && (file_exists($pathRoot.'/configuration.php'))) {
            return realpath($pathRoot);
        }

        return null;
    }

    /**
     * createCmsCli.
     */
    public function createCmsCli()
    {
        // We are a valid entry point.
        defined('_JEXEC') || define('_JEXEC', 1);
        defined('DS') || define('DS', \DIRECTORY_SEPARATOR);
        defined('JPATH_BASE') || define('JPATH_BASE', JOOMLA_SITE_PATH);

        // Load system defines
        if (file_exists(JOOMLA_SITE_INCLUDES_PATH.'/defines.php')) {
            require_once JOOMLA_SITE_INCLUDES_PATH.'/defines.php';
        }

        if (!defined('_JDEFINES')) {
            require_once JPATH_BASE.'/includes/defines.php';
        }

        $this->isJ4 = file_exists(JPATH_LIBRARIES.'/bootstrap.php');

        if ($this->isJ4) {
            $this->createCmsCliJ4();
            $this->startCliAppJ4();
        } else {
            $this->createCmsCliJ3();
            JLoader::registerAlias('JApplicationCliForJ3', '\\XTP_BUILD\\Extly\\Infrastructure\\Service\\Cms\\Joomla\\CliApplicationForJ3', '5.0');
            $this->startCliAppJ3();
        }

        // Configure error reporting to maximum for CLI output.
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }

    public function startCliAppJ3()
    {
        CMSFactory::getApplication('CliForJ3');

        // System configuration
        if (!defined('JDEBUG')) {
            // System configuration.
            $config = new JConfig();
            define('JDEBUG', $config->debug);
        }
    }

    public function startCliAppJ4()
    {
        // libraries/src/Application/ConsoleApplication.php, line 72
        // parent::__construct($config);

        // Boot the DI container
        $container = CMSFactory::getContainer();
        $container->alias('session', 'session.cli')
            ->alias('JSession', 'session.cli')
            ->alias(CMSSession::class, 'session.cli')
            ->alias(JoomlaSession::class, 'session.cli')
            ->alias(CMSSessionInterface::class, 'session.cli');

        $app = CMSFactory::getContainer()->get(J4CMSConsoleApp::class);
        CMSFactory::$application = $app;
        // $app->execute();
    }

    public function loadExtensionLanguage($extension)
    {
        // Load Library language
        $lang = CMSFactory::getLanguage();

        // Try the xtdir4alg_cli file in the current language
        // (without allowing the loading of the file in the default language)
        $lang->load($extension, JPATH_SITE, null, false, false)

        // Fallback to the xtdir4alg_cli file in the default language
        || $lang->load($extension, JPATH_SITE, null, true);

        $site = CMSFactory::getApplication('Site');
        $site->loadLanguage();
    }

    /**
     * boot.
     *
     * @param LumenApplication $pp Param
     */
    public function boot(ArrayAccess $app)
    {
        $connections = $app['config']['database.connections'];

        $connections['mysql']['host'] = $this->getConnectionHost();
        $connections['mysql']['database'] = $this->getConnectionDatabase();
        $connections['mysql']['username'] = $this->getConnectionUsername();
        $connections['mysql']['password'] = $this->getConnectionPassword();
        $connections['mysql']['prefix'] = $this->getConnectionPrefix();

        $app['config']['database.connections'] = $connections;
    }

    public function getConnectionHost()
    {
        return CMSFactory::getConfig()->get('host');
    }

    public function getConnectionDatabase()
    {
        return CMSFactory::getConfig()->get('db');
    }

    public function getConnectionUsername()
    {
        return CMSFactory::getConfig()->get('user');
    }

    public function getConnectionPassword()
    {
        return CMSFactory::getConfig()->get('password');
    }

    public function getConnectionPrefix()
    {
        return CMSFactory::getConfig()->get('dbprefix');
    }

    public function translate($value, $default = null)
    {
        $text = CMSText::_($value);

        if (!empty($text)) {
            return $text;
        }

        return CMSText::_($default);
    }

    public function getSetting($key, $default = null, $component = null)
    {
        if (!$component) {
            $component = $this->component;
        }

        if (!$component) {
            throw new CmsException('JoomlaService: Undefined component.');
        }

        if ($this->isCli()) {
            $params = CMSComponentHelper::getParams($component);
        } else {
            if ($this->isAdmin()) {
                $params = CMSComponentHelper::getParams($component);
            } else {
                $params = CMSFactory::getApplication()->getParams($component);
            }
        }

        return $params->get($key, $default);
    }

    public function getProduct($key)
    {
        return $this->config['product'][$key];
    }

    public function getItem($id)
    {
    }

    public function getPlugin($name)
    {
        return new Plugin($this, $name);
    }

    public function getContentTypeEnumFactory()
    {
        return new ContentTypeEnum(ContentTypeEnum::JOOMLA_ARTICLE);
    }

    public function getUser($id = null)
    {
        return User::create($id);
    }

    public function getSession()
    {
        return new Session();
    }

    public function getMailClient()
    {
        return new Mail();
    }

    public function getProductInfo()
    {
        return new ProductInfo($this);
    }

    public function getRouter()
    {
        return new Router();
    }

    public function getSitename()
    {
        return CMSFactory::getConfig()->get('sitename');
    }

    public function getTemporaryFolderPath()
    {
        return CMSFactory::getConfig()->get('tmp_path');
    }

    public function getLogFolderPath()
    {
        return CMSFactory::getConfig()->get('log_path');
    }

    public function getRootFolderPath()
    {
        return JPATH_ROOT;
    }

    public function getCacheFolderPath()
    {
        return JPATH_CACHE;
    }

    public function getRootUri()
    {
        if (isset($this->config[CmsSettingsRegistry::CONFIG_CMS_BASE_URL])) {
            return $this->config[CmsSettingsRegistry::CONFIG_CMS_BASE_URL];
        }

        $baseUrl = $this->getSetting(CmsSettingsRegistry::CONFIG_CMS_BASE_URL);

        if (!empty($baseUrl)) {
            return $baseUrl;
        }

        if ($this->isCli()) {
            throw new CmsException('Minimum configuration (CMS_BASE_URL) has not been provided.');
        }

        return CMSUri::root();
    }

    public function loadTemplate($key)
    {
        $options = new CMSRegistry();
        $options->set('component', 'none');
        $options->set('client', 'site');

        return CMSLayoutHelper::render($key, null, JPATH_XT_COMPONENT_LAYOUTS, $options);
    }

    public function slugify($title)
    {
        return str_replace('&', '-', CMSApplicationHelper::stringUrlSafe($title));
    }

    public function cleanCache($includedComponents)
    {
        $includedComponents = Estring::create($includedComponents)
            ->convertListToArray();

        if (empty($includedComponents)) {
            return;
        }

        foreach ($includedComponents as $component) {
            $this->cleanCacheByGroup($component);
        }

        return true;
    }

    public function getPageLimit()
    {
        return CMSFactory::getConfig()->get('list_limit');
    }

    public function getWebserviceSecretKey()
    {
        return sha1(CMSFactory::getConfig()->get('secret').CMSFactory::getConfig()->get('password'));
    }

    public function getApiToken()
    {
        $webserviceApiToken = $this->getSetting(CmsSettingsRegistry::WEBSERVICE_API_KEY);

        if (!empty($webserviceApiToken)) {
            return $webserviceApiToken;
        }

        return CMSFactory::getSession()->getFormToken();
    }

    public function defineComponent($component)
    {
        $this->component = $component;

        defined('JPATH_COMPONENT_ADMINISTRATOR') ||
            define('JPATH_COMPONENT_ADMINISTRATOR', JPATH_ADMINISTRATOR.'/components/'.$component);

        // Load Library language
        $lang = CMSFactory::getLanguage();

        $lang->load($component, JPATH_ADMINISTRATOR, null, false, false)
            || $lang->load($component, JPATH_ADMINISTRATOR, null, true)
            || $lang->load($component, JPATH_SITE, null, false, false)
            || $lang->load($component, JPATH_SITE, null, true);

        defined('JPATH_XT_COMPONENT') || define(
            'JPATH_XT_COMPONENT',
            JPATH_ADMINISTRATOR.'/components/'.$component
        );

        defined('JPATH_XT_COMPONENT_LAYOUTS') || define('JPATH_XT_COMPONENT_LAYOUTS', JPATH_XT_COMPONENT.'/layouts');
    }

    /**
     * setServerHttpHost.
     *
     * @param string $cmsBaseUrl Param
     */
    public function setServerHttpHost($cmsBaseUrl)
    {
        $_SERVER['HTTP_HOST'] = UrlHelper::create()->getHost($cmsBaseUrl);
    }

    /**
     * getTimezone.
     *
     * @return string
     */
    public function getTimezone()
    {
        return CMSFactory::getConfig()->get('offset');
    }

    /**
     * isMultilingualSite.
     *
     * @return string
     */
    public function isMultilingualSite()
    {
        // 'JLanguageMultilang', '\\Joomla\\CMS\\Language\\Multilanguage'
        // JLanguageMultilang::isEnabled()
        return CMSPluginHelper::isEnabled('system', 'languagefilter');
    }

    /**
     * getCurrentSefCode.
     *
     * @return string
     */
    public function getCurrentSefCode()
    {
        $webInput = new CMSWebInput();
        $lang = $webInput->get('lang');

        if (!empty($lang)) {
            // Check if Joomla has already auto-translated the SefCode to LangCode
            if (false !== strpos($lang, '-')) {
                // Return a SefCode!
                return $this->translateLangCode2SefCode($lang);
            }

            return $lang;
        }

        $uri = CMSUri::getInstance();
        $lang = $uri->getVar('lang');

        if (!empty($lang)) {
            return $lang;
        }

        if (!$this->isCli()) {
            return self::getDefaultSefCode();
        }

        $cliInput = new CMSCliInput();
        $lang = $cliInput->get('lang');

        if (!empty($lang)) {
            return $lang;
        }

        // Not detected, then the default Sef Code
        return self::getDefaultSefCode();
    }

    /**
     * getCurrentLanguageCode.
     *
     * @return string
     */
    public function getCurrentLanguageCode()
    {
        $langSefCode = $this->getCurrentSefCode();

        if (!empty($langSefCode)) {
            return $this->translateSefCode2LangCode($langSefCode);
        }

        $siteLanguage = CMSFactory::getLanguage()->getTag();

        if (!empty($siteLanguage)) {
            return $siteLanguage;
        }

        return self::getDefaultLanguageCode();
    }

    /**
     * getDefaultLanguageCode.
     *
     * @return string
     */
    public function getDefaultLanguageCode()
    {
        return $this->getSetting('site', 'en-GB', 'com_languages');
    }

    /**
     * getDefaultSefCode.
     *
     * @return string
     */
    public function getDefaultSefCode()
    {
        $langCode = $this->getDefaultLanguageCode();

        return $this->translateLangCode2SefCode($langCode);
    }

    public function translateSefCode2LangCode($langSefCode)
    {
        $langs = CMSLanguageHelper::getLanguages('sef');

        if (isset($langs[$langSefCode])) {
            $lang = $langs[$langSefCode];

            return $lang->lang_code;
        }

        // There is some inconsistency somewhere,
        // the language has been unpublished

        return null;
    }

    public function translateLangCode2SefCode($langCode)
    {
        $langs = CMSLanguageHelper::getLanguages('lang_code');

        if (isset($langs[$langCode])) {
            $lang = $langs[$langCode];

            return $lang->sef;
        }

        // There is some inconsistency somewhere,
        // the language has been unpublished

        return null;
    }

    /**
     * getCurrentLanguageCodeFilter.
     *
     * @return string
     */
    public function getCurrentLanguageCodeFilter()
    {
        return ['*', $this->getCurrentLanguageCode()];
    }

    public function getSefCodes()
    {
        $langs = CMSLanguageHelper::getLanguages('sef');

        return array_keys($langs);
    }

    public function isAdmin()
    {
        return CMSFactory::getApplication()->isAdmin();
    }

    public function getMenu($client = 'site')
    {
        $isMultilingualSite = $this->isMultilingualSite();

        if (!$isMultilingualSite) {
            return CMSFactory::getApplication()->getMenu($client);
        }

        $currentLanguageObject = $this->getCurrentLanguageObject();

        $options = [
            'language' => $currentLanguageObject,
        ];

        // Create a Menu object
        $classname = '\JMenu'.ucfirst($client);
        $menu = new $classname($options);

        return $menu;
    }

    /**
     * Clean the cache.
     *
     * @param string $group    The cache group
     * @param int    $clientId The ID of the client
     */
    protected function cleanCacheByGroup($group, $clientId = 0)
    {
        $options = [
            'defaultgroup' => $group,
            'cachebase' => $clientId ?
                JPATH_ADMINISTRATOR.'/cache' :
                CMSFactory::getConfig()->get('cache_path', JPATH_SITE.'/cache'),
            'result' => true,
        ];

        try {
            $cache = CMSCache::getInstance('callback', $options);
            $cache->clean();
        } catch (CMSCacheException $exception) {
            $options['result'] = false;
        }

        if (class_exists('JEventDispatcher')) {
            JEventDispatcher::getInstance()->trigger('onContentCleanCache', $options);
        }
    }

    private function createCmsCliJ3()
    {
        // Get the framework.
        require_once JPATH_LIBRARIES.'/import.legacy.php';

        // Bootstrap the CMS libraries.
        require_once JPATH_LIBRARIES.'/cms.php';

        // Import the configuration.
        require_once JPATH_CONFIGURATION.'/configuration.php';
    }

    private function createCmsCliJ4()
    {
        // Get the framework.
        require_once JPATH_BASE.'/includes/framework.php';
    }

    private function createCms()
    {
        // Avoid notices - REQUEST_METHOD
        $backupRequestMethod = null;

        if (isset($_SERVER['REQUEST_METHOD'])) {
            $backupRequestMethod = $_SERVER['REQUEST_METHOD'];
            $_SERVER['REQUEST_METHOD'] = null;
        }

        if (!isset($_SERVER['HTTP_HOST']) && (!empty($this->config[CmsSettingsRegistry::CONFIG_CMS_BASE_URL]))) {
            $this->setServerHttpHost($this->config[CmsSettingsRegistry::CONFIG_CMS_BASE_URL]);
        }
        // Avoid notices - REQUEST_METHOD

        $this->createCmsCli();

        // Avoid notices
        if ($backupRequestMethod) {
            $_SERVER['REQUEST_METHOD'] = $backupRequestMethod;
        }
        // Avoid notices
    }

    private function getCurrentLanguageObject()
    {
        $conf = CMSFactory::getConfig();
        $locale = $this->getCurrentLanguageCode();
        $debug = $conf->get('debug_lang');

        return CMSLanguage::getInstance($locale, $debug);
    }
}
