<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:17 */

/*
 * @package     Extly Infrastructure Support
 *              Beyond the JDocument, the Asset Tags Builder manages
 *                the generation of script and style tags for an Html Document.
 *
 * @author      Extly, CB. <team@extly.com>
 * @copyright   Copyright (c)2007-2019 Extly, CB. All rights reserved.
 * @license     http://www.opensource.org/licenses/mit-license.html  MIT License
 *
 * @see         https://www.extly.com
 */

namespace XTP_BUILD\Extly\Infrastructure\Service\Cms\Joomla;

use XTP_BUILD\Extly\Infrastructure\Support\HtmlAsset\Asset\InlineScriptTag;
use XTP_BUILD\Extly\Infrastructure\Support\HtmlAsset\Asset\InlineStyleTag;
use XTP_BUILD\Extly\Infrastructure\Support\HtmlAsset\Asset\LinkStylesheetTag;
use XTP_BUILD\Extly\Infrastructure\Support\HtmlAsset\Asset\ScriptTag;
use XTP_BUILD\Extly\Infrastructure\Support\HtmlAsset\Repository as HtmlAssetRepository;
use Joomla\CMS\Factory as CMSFactory;
use Joomla\CMS\HTML\HTMLHelper as CMSHTMLHelper;

class ScriptHelper
{
    public static function addStyleSheet($url)
    {
        return self::addDeferredStyle($url);
    }

    public static function addScript($url, $options = [], $attribs = [])
    {
        return self::addScriptVersion($url, $options, $attribs);
    }

    public static function addScriptVersion($url, $options = [], $attribs = [])
    {
        if ((is_string($options)) && ((false === strpos($url, '?')))) {
            $url = $url.'?'.$options;
        }

        if (!is_array($attribs)) {
            $attribs = [];
        }

        return self::addDeferredScript($url, $attribs);
    }

    public static function addScriptDeclaration($script)
    {
        CMSFactory::getDocument()->addScriptDeclaration($script);

        // Alternative XT Html Asset Tags Builder
        $inlineScriptTag = InlineScriptTag::create($script);
        HtmlAssetRepository::getInstance()->push($inlineScriptTag);
    }

    public static function addStyleDeclaration($style)
    {
        CMSFactory::getDocument()->addStyleDeclaration($style);

        // Alternative XT Html Asset Tags Builder
        $inlineStyleTag = InlineStyleTag::create($style);
        HtmlAssetRepository::getInstance()->push($inlineStyleTag);
    }

    /**
     * addDeferredExtensionScript.
     *
     * Example: ScriptHelper::addDeferredExtensionScript('lib_xtdir4alg/app/autocomplete.min.js');
     *
     * @param string $extensionScript Param
     * @param mixed  $attribs         Html Attributes
     */
    public static function addDeferredExtensionScript($extensionScript, $attribs = [])
    {
        $defaultAttribs = [
            'defer' => true,
        ];
        $attribs = array_merge($defaultAttribs, $attribs);

        $include = CMSHTMLHelper::script(
            $extensionScript,
            [
                'relative' => true,
                'pathOnly' => true,
            ],
            $attribs
        );
        self::addScriptToDocument($include, $attribs);

        // Alternative XT Html Asset Tags Builder
        $scriptTag = ScriptTag::create($include, $attribs);
        HtmlAssetRepository::getInstance()->push($scriptTag);
    }

    /**
     * addDeferredScript.
     *
     * Example: ScriptHelper::addDeferredScript('https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js');
     *
     * @param string $extensionScriptHref Param
     * @param mixed  $attribs             Html Attributes
     */
    public static function addDeferredScript($extensionScriptHref, $attribs = [])
    {
        $defaultAttribs = ['defer' => true, 'crossorigin' => 'anonymous'];
        $attribs = array_merge($defaultAttribs, $attribs);

        CMSFactory::getDocument()->addScript($extensionScriptHref, [], $attribs);

        // Alternative XT Html Asset Tags Builder
        $scriptTag = ScriptTag::create($extensionScriptHref, $attribs);
        HtmlAssetRepository::getInstance()->push($scriptTag);
    }

    /**
     * addDeferredStyle.
     *
     * Example: ScriptHelper::addDeferredStyle('https://cdn.jsdelivr.net/npm/...instantsearch.min.css');
     *
     * @param string $extensionStyleHref Param
     */
    public static function addDeferredStyle($extensionStyleHref)
    {
        $script = '!function(e){var t=document.createElement("link");t.rel="stylesheet",t.href="'.
            $extensionStyleHref.
            '",t.type="text/css";var n=document.getElementsByTagName("link")[0];n.parentNode.insertBefore(t,n)}();';
        CMSFactory::getDocument()->addScriptDeclaration($script);

        // Alternative XT Html Asset Tags Builder
        $inlineScriptTag = InlineScriptTag::create($script);
        HtmlAssetRepository::getInstance()->push($inlineScriptTag);
    }

    /**
     * addInlineStylesheet.
     *
     * Example: ScriptHelper::addInlineStylesheet('mod_xtdir4alg_autocomplete/xtdir4alg_autocomplete.min.css');
     *
     * @param string $extensionStyle
     * @param mixed  $extensionRelativeStylesheet
     * @param mixed  $options
     */
    public static function addInlineStylesheet($extensionRelativeStylesheet, $options = [])
    {
        $defaultOptions = ['version' => 'auto', 'relative' => true, 'pathOnly' => true];
        $options = array_merge($defaultOptions, $options);

        $uriPath = CMSHTMLHelper::stylesheet($extensionRelativeStylesheet, $options);
        $filePath = JPATH_ROOT.$uriPath;

        // The Uri can be mapped to a directory (subfolders?)
        if (file_exists($filePath)) {
            $styleDeclaration = file_get_contents($filePath);

            CMSFactory::getDocument()->addStyleDeclaration($styleDeclaration);

            // Alternative XT Html Asset Tags Builder
            $inlineStyleTag = InlineStyleTag::create($styleDeclaration);
            HtmlAssetRepository::getInstance()->push($inlineStyleTag);

            return true;
        }

        // Just load the StyleSheet
        CMSFactory::getDocument()->addStyleSheet($uriPath);

        // Alternative XT Html Asset Tags Builder
        $stylesheetTag = LinkStylesheetTag::create($uriPath);
        HtmlAssetRepository::getInstance()->push($stylesheetTag);

        return true;
    }

    /**
     * addInlineScript (Extension).
     *
     * Example: ScriptHelper::addInlineScript('lib_xtdir4alg/app/autocomplete.min.js');
     *
     * @param mixed $extensionRelativeScript
     * @param mixed $options
     */
    public static function addInlineScript($extensionRelativeScript, $options = [])
    {
        $defaultOptions = ['version' => 'auto', 'relative' => true, 'pathOnly' => true];
        $options = array_merge($defaultOptions, $options);

        $uriPath = CMSHTMLHelper::script($extensionRelativeScript, $options);
        $filePath = JPATH_ROOT.$uriPath;

        // The Uri can be mapped to a directory (subfolders?)
        if (file_exists($filePath)) {
            $scriptDeclaration = file_get_contents($filePath);

            CMSFactory::getDocument()->addScriptDeclaration($scriptDeclaration);

            // Alternative XT Html Asset Tags Builder
            $inlineScriptTag = InlineScriptTag::create($scriptDeclaration);
            HtmlAssetRepository::getInstance()->push($inlineScriptTag);

            return true;
        }

        return self::addDeferredExtensionScript($extensionRelativeScript);
    }

    private static function addScriptToDocument($include, $attribs)
    {
        // Pasted from libraries/src/HTML/HTMLHelper.php, 730
        $options = [];

        // If inclusion is required
        $document = CMSFactory::getDocument();

        // If there is already a version hash in the script reference (by using deprecated MD5SUM).
        if ($pos = false !== strpos($include, '?')) {
            $options['version'] = substr($include, $pos + 1);
        }

        $document->addScript($include, $options, $attribs);
    }
}
