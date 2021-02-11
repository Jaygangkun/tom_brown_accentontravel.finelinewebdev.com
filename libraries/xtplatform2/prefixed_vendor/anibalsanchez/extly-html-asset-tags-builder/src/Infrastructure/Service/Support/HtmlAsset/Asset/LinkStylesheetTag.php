<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:13 */

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
use XTP_BUILD\Extly\Infrastructure\Support\HtmlAsset\Repository;

final class LinkStylesheetTag extends HtmlAssetTagAbstract implements HtmlAssetTagInterface
{
    use CreatorTrait;

    public function __construct(string $href, array $attributes = [])
    {
        $defaultAttributes = [
            'position' => Repository::GLOBAL_POSITION_BODY,
        ];

        $script = '!function(e){var t=document.createElement("link");t.rel="stylesheet",t.href="'.
            $href.
            '",t.type="text/css";var n=document.getElementsByTagName("link")[0];n.parentNode.insertBefore(t,n)}();';

        parent::__construct('script', $script, array_merge($defaultAttributes, $attributes));
    }
}
