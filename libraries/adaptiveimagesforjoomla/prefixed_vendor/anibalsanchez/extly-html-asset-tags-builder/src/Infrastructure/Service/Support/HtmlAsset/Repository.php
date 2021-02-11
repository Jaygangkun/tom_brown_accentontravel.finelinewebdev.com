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

namespace XTP_BUILD\Extly\Infrastructure\Support\HtmlAsset;

use XTP_BUILD\Extly\Infrastructure\Creator\CreatorTrait;
use XTP_BUILD\Extly\Infrastructure\Creator\SingletonTrait;
use XTP_BUILD\Extly\Infrastructure\Support\HtmlAsset\Asset\HtmlAssetTagInterface;

final class Repository
{
    use CreatorTrait;
    use SingletonTrait;

    const HTML_POSITION = 'position';
    const HTML_PRIORITY = 'priority';

    const GLOBAL_POSITION_HEAD = 'head';
    const GLOBAL_POSITION_BODY = 'bottom';

    private $assetTagCollection;

    public function __construct()
    {
        $this->assetCollection = AssetCollection::make();
    }

    public function push(HtmlAssetTagInterface $htmlAsset)
    {
        $this->assetCollection->push($htmlAsset);

        return $this;
    }

    public function getAssetTagsByPosition($positionName)
    {
        return $this->assetCollection
            ->filter(function (HtmlAssetTagInterface $item) use ($positionName) {
                return $item->getPosition() === $positionName;
            })
            ->sortBy(function (HtmlAssetTagInterface $item) {
                return $item->getPriority();
            });
    }
}
