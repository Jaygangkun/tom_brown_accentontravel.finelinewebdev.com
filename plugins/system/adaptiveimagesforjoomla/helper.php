<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Search for Algolia" on 2019-08-30 11:27:29 */

/*
 * @package    XT Adaptive Images PRO
 *
 * @author     Extly, CB <team@extly.com>
 * @copyright  Copyright (c)2007-2019 Extly, CB All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 * @link       https://www.extly.com
 */

defined('_JEXEC') or die;

use XTP_BUILD\Extly\AdaptiveImages\Common\ImageProcessor;
use XTP_BUILD\Extly\AdaptiveImages\Common\LazyLoadHelper;
use XTP_BUILD\Extly\AdaptiveImages\Common\SrcsetHelper;
use XTP_BUILD\Extly\Infrastructure\Service\Cms\Joomla\ScriptHelper;
use Joomla\CMS\Factory as CMSFactory;
use Joomla\CMS\HTML\HTMLHelper as CMSHTMLHelper;
use Joomla\CMS\Uri\Uri as CMSUri;

/**
 * AdaptiveImagesForJoomlaHelper class - Plugin that replaces media urls with Adaptive Images urls.
 *
 * @since       1.0
 */
class AdaptiveImagesForJoomlaHelper
{
    private $params;

    private $pass = false;

    private $currentSet;

    private $aiCachePath;

    private $imageIgnoreFiles;

    private $imageProcessor;

    private $hasCdn = false;

    private $isSsl = false;

    private $srcsetDictionary = [];

    /**
     * AdaptiveImagesForJoomlaHelper.
     *
     * @param object &$params Param
     */
    public function __construct(&$params)
    {
        $this->params = $params;
        $this->hasCdn = preg_replace(['#^.*\://#', '#/$#'], '', $this->getCdnParam());
        $this->params->set('tag_attribs', $this->getSearchTagAttributes());

        $this->isSsl = (
            (isset($_SERVER['HTTPS']) && 'ON' === strtoupper($_SERVER['HTTPS']))
            || (isset($_SERVER['SERVER_PORT']) && 443 === strtoupper($_SERVER['SERVER_PORT']))
            || (0 === strpos(CMSUri::root(), 'https://'))
        );

        $this->initSetList();
        $sets = $this->params->get('sets');

        if (empty($sets)) {
            return;
        }

        $this->pass = true;
        $this->aiCachePath = $params->get('ai_cache_path', 'media/xt-adaptive-images');

        $this->imageIgnoreFiles = explode(',', str_replace(['\n', ' '], [',', ''], $this->params->get('image_ignorefiles')));

        require_once JPATH_ROOT.'/libraries/adaptiveimagesforjoomla/vendor/autoload.php';

        if (!$this->imageProcessor) {
            $this->imageProcessor = new ImageProcessor(
                JPATH_ROOT,
                $this->aiCachePath,
                $params->get('resolutions', '1382,992,768,480'),
                $params->get('jpg_quality', '75'),
                $params->get('sharpen', true)
            );

            $this->imageProcessor->setGenerateSrcset($params->get('generate_srcset'));
        }
    }

    public function addScripts()
    {
        if ($this->params->get('retina_displays')) {
            ScriptHelper::addScriptDeclaration("document.cookie='resolution='+Math.max(screen.width,screen.height)+(\"devicePixelRatio\" in window ? \",\"+devicePixelRatio : \",1\")+'; path=/';");
        } else {
            ScriptHelper::addScriptDeclaration("document.cookie='resolution='+Math.max(screen.width,screen.height)+'; path=/';");
        }

        if ($this->params->get('enable_lazyload')) {
            $detectionClass = $this->params->get('detection_class', 'xt-lazy-img');
            $detectionClass = str_replace(' ', '', $detectionClass);
            $classes = explode(',', $detectionClass);

            if (empty($classes)) {
                $this->params->set('enable_lazyload', false);
            } else {
                $this->params->set('lazyload_classes', $classes);
                $selectors = '.'.implode(',.', $classes);

                if ($this->params->get('lazyload_library')) {
                    // Vanilla Lazy Load (2.0.0-beta.2)
                    ScriptHelper::addDeferredExtensionScript('plg_system_adaptiveimagesforjoomla/lazyload.min.js');
                    ScriptHelper::addScriptDeclaration('document.addEventListener("DOMContentLoaded", function() { lazyload(document.querySelectorAll("'.$selectors.'")); });');
                } else {
                    // JQuery Lazy Load (1.9.7)
                    CMSHTMLHelper::_('jquery.framework');
                    ScriptHelper::addDeferredExtensionScript('plg_system_adaptiveimagesforjoomla/jquery.lazyload.min.js');
                    ScriptHelper::addScriptDeclaration('jQuery(document).ready(function() { jQuery("'.$selectors.'").lazyload(); });');
                }
            }
        }
    }

    /**
     * apply.
     */
    public function apply()
    {
        if (!$this->pass) {
            return;
        }

        $html = CMSFactory::getApplication()->getBody();

        $this->replace($html);

        if (($this->params->get('generate_srcset')) && (!empty($this->srcsetDictionary))) {
            $srcsetHelper = new SrcsetHelper();
            $srcsetHelper->setSrcsetDictionary($this->srcsetDictionary);
            $srcsetHelper->setSizes($this->params->get('generate_srcset_sizes', '100vw'));
            $srcsetHelper->addSrcsetSizes($html, $this);
        }

        if ($this->params->get('enable_lazyload')) {
            $lazyLoadHelper = new LazyLoadHelper($this);

            if ($this->params->get('lazyload_library')) {
                $lazyLoadHelper->setLibMode(LazyLoadHelper::VANILLA_LAZY_LOAD);
            }

            $lazyLoadHelper->replaceLazyLoad($html, $this->params->get('lazyload_classes'), $this);
        }

        $this->cleanLeftoverJunk($html);

        CMSFactory::getApplication()->setBody($html);
    }

    /**
     * fileIsIgnored.
     *
     * @param string $file Param
     *
     * @return bool
     */
    public function fileIsIgnored($file)
    {
        foreach ($this->currentSet->ignorefiles as $ignore) {
            if (($ignore)
                && ((false !== strpos($file, $ignore))
                || (false !== strpos(htmlentities($file), $ignore)))) {
                return true;
            }
        }

        return false;
    }

    /**
     * filetypeIsIgnored.
     *
     * @param string $file Param
     *
     * @return bool
     */
    public function filetypeIsIgnored($file)
    {
        return !in_array(pathinfo($file, PATHINFO_EXTENSION), $this->currentSet->filetypes, true);
    }

    /**
     * imageFileIsIgnored.
     *
     * @param string $file Param
     *
     * @return bool
     */
    public function imageFileIsIgnored($file)
    {
        foreach ($this->imageIgnoreFiles as $ignore) {
            if (($ignore)
                && ((false !== strpos($file, $ignore))
                || (false !== strpos(htmlentities($file), $ignore)))) {
                return true;
            }
        }

        return false;
    }

    /**
     * protectString.
     *
     * @param string $string Param
     */
    public function protectString($string)
    {
        if ($this->isEditPage()) {
            $string = preg_replace('#(<'.'form [^>]*(id|name)="(adminForm|postform)".*?</form>)#si', '{nocdn}\1{/nocdn}', $string);
        }

        if (false === strpos($string, '{nocdn}') || false === strpos($string, '{/nocdn}')) {
            $string = str_replace(['{nocdn}', '{/nocdn}'], '', $string);

            return [$string];
        }

        $string = str_replace(['{nocdn}', '{/nocdn}'], '[[CDN_SPLIT]]', $string);

        return explode('[[CDN_SPLIT]]', $string);
    }

    /**
     * isAdmin - check if page is an admin page.
     *
     * @param bool $blockLogin Param
     *
     * @return bool
     */
    protected function isAdmin($blockLogin = false)
    {
        return
                CMSFactory::getApplication()->isAdmin()
                && (!$blockLogin || 'com_login' !== CMSFactory::getApplication()->input->get('option'))
                && 'preview' !== CMSFactory::getApplication()->input->get('task')
        ;
    }

    protected function getCdnParam($setid = null)
    {
        $cdn = $this->params->get('cdn'.$setid);

        if ((!$setid) && (empty($cdn))) {
            return CMSUri::root();
        }

        return $cdn;
    }

    /**
     * getSearchTagAttributes.
     *
     * @return string
     */
    private function getSearchTagAttributes()
    {
        $attributes = [
            'href=',
            'src=',
            'data-original=',
            'longdesc=',
            'poster=',
            '@import',
            'name="movie" value=',
            'property="og:image" content=',
            'rel="{\'link\':',
        ];

        return str_replace(['"', '=', ' '], ['["\']?', '\s*=\s*', '\s+'], implode('|', $attributes));
    }

    /**
     * initSetList.
     */
    private function initSetList()
    {
        $sets = [];

        // Adaptiveimagesforjoomla-pro
        $nrOfSets = 5;

        for ($i = 1; $i <= $nrOfSets; ++$i) {
            $sets[] = $this->initSetParams($i);
        }

        $this->params->set('sets', $sets);

        $this->removeEmptySets();
    }

    /**
     * initSetParams.
     *
     * @param int $setid Param
     */
    private function initSetParams($setid = 1)
    {
        $index = $setid;
        $setid = ($setid <= 1) ? '' : '_'.(int) $setid;

        if (($index > 1) && (!preg_replace(['#^.*\://#', '#/$#'], '', $this->getCdnParam($setid)))) {
            return false;
        }

        $enableHttps = $this->params->get('enable_https'.$setid, true);

        // If is SSL and not enabled SSL at CDN, avoid any processing
        if (($this->isSsl) && (!$enableHttps)) {
            return false;
        }

        /*
        $filetypes = str_replace('-', ',', implode(',', $this->params->get('{'filetypes' . $setid}));
        $filetypes = explode(',', $filetypes);
        */

        $filetypes = [];

        if ($this->params->get('gif'.$setid, true)) {
            $filetypes[] = 'gif';
        }

        if ($this->params->get('jpg_jpeg'.$setid, true)) {
            $filetypes[] = 'jpg';
            $filetypes[] = 'jpeg';
        }

        if ($this->params->get('png'.$setid, true)) {
            $filetypes[] = 'png';
        }

        $extratypes = preg_replace('#\s#', '', $this->params->get('extratypes'.$setid));

        if ($extratypes) {
            $filetypes = array_merge($filetypes, explode(',', $extratypes));
        }

        $filetypes = array_unique(array_diff($filetypes, ['', 'x']));

        if (empty($filetypes)) {
            return false;
        }

        $currentSet = new stdClass();
        $currentSet->filetypes = $filetypes;

        $currentSet->enable_in_scripts = $this->params->get('enable_in_scripts'.$setid, false);

        // Adaptiveimagesforjoomla-pro
        $currentSet->protocol = $this->isSsl ? 'https://' : 'http://';

        $currentSet->cdn = preg_replace('#/$#', '', $this->getCdnParam($setid));
        $currentSet->enable_https = $enableHttps;
        $currentSet->ignorefiles = explode(',', str_replace(['\n', ' '], [',', ''], $this->params->get('ignorefiles'.$setid)));

        $root = preg_replace(['#^/#', '#/$#'], '', $this->params->get('root'.$setid)).'/';
        $currentSet->root = preg_replace('#^/#', '', $root);

        $currentSet->searches = [];
        $currentSet->jsSearches = [];

        $this->currentSet = $currentSet;

        $this->setFiletypeSearches();
        $this->setCdnPaths();

        return $this->currentSet;
    }

    /**
     * removeEmptySets.
     */
    private function removeEmptySets()
    {
        $sets = $this->params->get('sets');

        foreach ($sets as $i => $set) {
            if ((!empty($set)) && (!empty($set->searches))) {
                continue;
            }

            unset($sets[$i]);
        }

        $this->params->set('sets', $sets);
    }

    /**
     * replace.
     *
     * @param string &$string Param
     */
    private function replace(&$string)
    {
        if (is_array($string)) {
            $this->replaceInList($string);

            return;
        }

        $stringArray = $this->protectString($string);

        foreach ($stringArray as $i => &$substring) {
            if ($i % 2) {
                continue;
            }

            $stringArray[$i] = $this->replaceBySetList($substring);
        }

        $string = implode('', $stringArray);
    }

    /**
     * replaceInList.
     *
     * @param string &$array Param
     */
    private function replaceInList(&$array)
    {
        foreach ($array as &$val) {
            $this->replace($val);
        }
    }

    /**
     * replaceBySetList.
     *
     * @param string $string Param
     *
     * @return string
     */
    private function replaceBySetList($string)
    {
        $sets = $this->params->get('sets');

        foreach ($sets as $set) {
            $this->currentSet = $set;
            $this->replaceBySet($string);
        }

        return $string;
    }

    /**
     * replaceBySet.
     *
     * @param string &$string Param
     */
    private function replaceBySet(&$string)
    {
        $this->replaceBySearchList($string, $this->currentSet->searches);

        if ((!empty($this->currentSet->enable_in_scripts))
            && (false !== strpos($string, '<script'))) {
            $this->replaceInJavascript($string);
        }
    }

    /**
     * replaceInJavascript.
     *
     * @param string &$string Param
     */
    private function replaceInJavascript(&$string)
    {
        $regex = '#<script(?:\s+(language|type)\s*=[^>]*)?>.*?</script>#si';

        if (preg_match_all($regex, $string, $stringparts, PREG_SET_ORDER) < 1) {
            return;
        }

        foreach ($stringparts as $stringpart) {
            $this->replaceInJavascriptStringPart($string, $stringpart);
        }
    }

    /**
     * replaceInJavascriptStringPart.
     *
     * @param string &$string    Param
     * @param string $stringpart Param
     */
    private function replaceInJavascriptStringPart(&$string, $stringpart)
    {
        $newstr = $stringpart['0'];

        if (!$this->replaceBySearchList($newstr, $this->currentSet->jsSearches)) {
            return;
        }

        $string = str_replace($stringpart['0'], $newstr, $string);
    }

    /**
     * replaceBySearchList.
     *
     * @param string &$string   Param
     * @param string &$searches Param
     */
    private function replaceBySearchList(&$string, &$searches)
    {
        $changed = 0;

        foreach ($searches as $search) {
            $changed = $this->replaceBySearch($string, $search);
        }

        return $changed;
    }

    /**
     * replaceBySearchList.
     *
     * @param string &$string Param
     * @param string &$search Param
     */
    private function replaceBySearch(&$string, &$search)
    {
        if (preg_match_all($search, $string, $matches, PREG_SET_ORDER) < 1) {
            return false;
        }

        $changed = false;

        foreach ($matches as $match) {
            $file = trim($match['3']);

            if (!$file) {
                continue;
            }

            if ($this->fileIsIgnored($file)) {
                continue;
            }

            if ($this->imageFileIsIgnored($file)) {
                $generatedAdaptedImage = false;
                $processedFile = $file;
            } else {
                $generatedAdaptedImage = true;
                $processedFile = $this->imageProcessor->getAdaptedImage($file);
            }

            $processedUrl = null;

            // Proceed with CDN on the regular file
            if (false === strpos($processedFile, $this->aiCachePath)) {
                if ($this->hasCdn) {
                    $processedUrl = $this->getCdnUrl($file).'/'.$file;
                    $replace = $match['1'].$processedUrl.$match['4'];
                    $string = str_replace($match['0'], $replace, $string);
                }
            } else {
                $parts = explode($this->aiCachePath, $processedFile);
                $file = $this->aiCachePath.$parts[1];

                if ($this->hasCdn) {
                    $processedUrl = $this->getCdnUrl($file).'/'.$file;
                    $replace = $match['1'].$processedUrl.$match['4'];
                } else {
                    $replace = $match['1'].$file.$match['4'];
                }

                $string = str_replace($match['0'], $replace, $string);
            }

            if (($generatedAdaptedImage) && ($processedUrl) && ($this->params->get('generate_srcset'))) {
                $srcset = $this->imageProcessor->getSrcSet();
                $this->srcsetDictionary[$processedUrl] = $this->getCdnUrls($srcset);
            }

            $changed = true;
        }

        return $changed;
    }

    /**
     * setFiletypeSearches.
     */
    private function setFiletypeSearches()
    {
        if (empty($this->currentSet->filetypes)) {
            return;
        }

        $urls = $this->getUrlList();

        foreach ($urls as $url) {
            $this->setSearchesByUrl($url);
        }
    }

    /**
     * getUrlList.
     */
    private function getUrlList()
    {
        // Domain url or root path
        $url = preg_quote(str_replace('https://', 'http://', CMSUri::root()), '#');
        $url .= '|'.preg_quote(str_replace('http://', '//', CMSUri::root()), '#');

        if ($this->currentSet->enable_https) {
            $url .= '|'.preg_quote(str_replace('http://', 'https://', CMSUri::root()), '#');
        }

        if (CMSUri::root(1)) {
            $url .= '|'.preg_quote(CMSUri::root(1).'/', '#');
        }

        $filetypes = implode('|', $this->currentSet->filetypes);
        $root = preg_quote($this->currentSet->root, '#');

        $urls = [];

        // Absolute path
        $urls[] = '(?:'.$url.')'.$root.'([^ \?QUOTES]+\.(?:'.$filetypes.')(?:\?[^QUOTES]*)?)';

        // Relative path
        $urls[] = 'LSLASH'.$root.'([a-z0-9-_]+/[^ \?QUOTES]+\.(?:'.$filetypes.')(?:\?[^QUOTES]*)?)';

        // Relative path - file in root
        $urls[] = 'LSLASH'.$root.'([a-z0-9-_]+[^ \?\/QUOTES]+\.(?:'.$filetypes.')(?:\?[^QUOTES]*)?)';

        return $urls;
    }

    /**
     * setSearchesByUrl.
     *
     * @param string $url Param
     */
    private function setSearchesByUrl($url)
    {
        $urlRegex = '\s*'.str_replace('QUOTES', '"\'', $url).'\s*';

        if ($this->currentSet->enable_in_scripts) {
            $urlRegexJs = str_replace('LSLASH', '', $urlRegex);

            // "..."
            $this->currentSet->jsSearches[] = '#((["\']))'.$urlRegexJs.'(["\'])#i';
        }

        $urlRegex = str_replace('LSLASH', '/?', $urlRegex);

        // Attrib="..."
        $this->currentSet->searches[] = '#((?:'.$this->params->get('tag_attribs').')\s*(["\']))'.$urlRegex.'(\2)#i';

        // Attrib=...
        $this->currentSet->searches[] = '#((?:'.$this->params->get('tag_attribs').')())'.$urlRegex.'([\s|>])#i';

        // Url(...) or url("...")
        $this->currentSet->searches[] = '#(url\(\s*((?:["\'])?))'.$urlRegex.'(\2\s*\))#i';

        // Add ')' to the no quote checks
        $urlRegex = '\s*'.str_replace('QUOTES', '"\'\)', $url).'\s*';

        // Url(...)
        $this->currentSet->searches[] = '#(url\(\s*())'.$urlRegex.'(\s*\))#i';
    }

    /**
     * getCdnUrl.
     *
     * @param string $file param
     *
     * @return string
     */
    private function getCdnUrl($file)
    {
        $cdns = $this->currentSet->cdns;

        return $this->currentSet->protocol.$cdns['0'];
    }

    /**
     * getCdnUrls.
     *
     * @param string $file  param
     * @param mixed  $files
     *
     * @return string
     */
    private function getCdnUrls($files)
    {
        $processed = [];

        foreach ($files as $key => $file) {
            $processed[$key] = $this->getCdnUrl($file).'/'.$file;
        }

        return $processed;
    }

    /**
     * setCdnPaths.
     */
    private function setCdnPaths()
    {
        $this->currentSet->cdns = explode(',', $this->currentSet->cdn);

        foreach ($this->currentSet->cdns as $i => $cdn) {
            $cdn = preg_replace('#^.*\://#', '', trim($cdn));
            $this->currentSet->cdns[$i] = $cdn;
        }
    }

    /**
     * cleanLeftoverJunk - Just in case you can't figure the method name out: this cleans the left-over junk.
     *
     * @param string &$string Param
     */
    private function cleanLeftoverJunk(&$string)
    {
        $string = str_replace(['{nocdn}', '{/nocdn}'], '', $string);
    }

    /**
     * isEditPage - check if page is an edit page.
     *
     * @return bool
     */
    private function isEditPage()
    {
        $option = CMSFactory::getApplication()->input->get('option');

        // Always return false for these components
        if (in_array($option, ['com_rsevents', 'com_rseventspro'], true)) {
            return 0;
        }

        $task = CMSFactory::getApplication()->input->get('task');
        $view = CMSFactory::getApplication()->input->get('view');

        if (false !== strpos($task, '.')) {
            $task = explode('.', $task);
            $task = array_pop($task);
        }

        if (false !== strpos($view, '.')) {
            $view = explode('.', $view);
            $view = array_pop($view);
        }

        return
                in_array($task, ['edit', 'form', 'submission'], true)
                || in_array($view, ['edit', 'form'], true)
                || in_array(CMSFactory::getApplication()->input->get('do'), ['edit', 'form'], true)
                || in_array(CMSFactory::getApplication()->input->get('layout'), ['edit', 'form', 'write'], true)
                || in_array(CMSFactory::getApplication()->input->get('option'), ['com_contentsubmit', 'com_cckjseblod'], true)
                || $this->isAdmin()
        ;
    }
}
