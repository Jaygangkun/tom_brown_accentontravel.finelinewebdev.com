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

namespace XTP_BUILD\Extly\Infrastructure\Support\HtmlAsset;

use XTP_BUILD\Extly\Infrastructure\Creator\CreatorTrait;
use XTP_BUILD\Extly\Infrastructure\Support\HtmlAsset\Asset\HtmlAssetTagInterface;
use XTP_BUILD\Studiow\HTML\Element;

final class HtmlAssetTagsBuilder
{
    use CreatorTrait;

    private $repository;

    public function __construct(Repository $repository = null)
    {
        if (!$repository) {
            // No Container, so let's singleton it
            $repository = Repository::getInstance();
        }

        $this->repository = $repository;
    }

    public function generate($positionName)
    {
        $assetTags = $this->repository->getAssetTagsByPosition($positionName);

        return $this->build($assetTags);
    }

    private function build($assetTags)
    {
        $buffer = [];

        foreach ($assetTags as $assetTag) {
            $buffer[] = $this->buildTag($assetTag);
        }

        return implode('', $buffer);
    }

    private function buildTag(HtmlAssetTagInterface $assetTag)
    {
        return (string) (new Element(
            $assetTag->getTag(),
            $assetTag->getInnerHTML(),
            $assetTag->getAttributes()
                ->forget(Repository::HTML_POSITION)
                ->forget(Repository::HTML_PRIORITY)
                ->toArray()
        ));
    }
}
