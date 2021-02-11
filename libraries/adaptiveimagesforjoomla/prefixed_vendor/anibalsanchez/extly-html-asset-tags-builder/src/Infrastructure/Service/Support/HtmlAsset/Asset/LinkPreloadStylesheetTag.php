<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:14 */

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

namespace XTP_BUILD\Extly\Infrastructure\Support\HtmlAsset\Asset;

use XTP_BUILD\Extly\Infrastructure\Creator\CreatorTrait;

final class LinkPreloadStylesheetTag extends HtmlAssetTagAbstract implements HtmlAssetTagInterface
{
    use CreatorTrait;

    public function __construct(string $href, array $attributes = [])
    {
        $defaultAttributes = [
            'rel' => 'preload',
            'as' => 'style',
            'onload' => 'this.rel = "stylesheet"',
        ];

        $attributes['href'] = $href;

        parent::__construct('link', '', array_merge($defaultAttributes, $attributes));
    }
}
