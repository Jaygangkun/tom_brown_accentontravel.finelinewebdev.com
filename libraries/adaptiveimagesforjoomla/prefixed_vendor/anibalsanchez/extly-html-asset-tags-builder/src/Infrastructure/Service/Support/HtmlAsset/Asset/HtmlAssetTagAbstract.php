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

use XTP_BUILD\Extly\Infrastructure\Support\HtmlAsset\Repository;
use XTP_BUILD\Illuminate\Support\Collection;

class HtmlAssetTagAbstract
{
    protected $tag;

    protected $innerHtml;

    protected $attributes;

    protected function __construct(string $tag, string $innerHtml, array $attributes = [])
    {
        $this->tag = $tag;
        $this->innerHtml = $innerHtml;
        $this->attributes = Collection::make($attributes);
    }

    /**
     * Get the value of tag.
     */
    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * Get the value of innerHtml.
     */
    public function getInnerHtml(): string
    {
        return $this->innerHtml;
    }

    /**
     * Get the value of attributes.
     */
    public function getAttributes(): Collection
    {
        return clone $this->attributes;
    }

    public function getPosition(): string
    {
        if (!$this->attributes->has(Repository::HTML_POSITION)) {
            return Repository::GLOBAL_POSITION_HEAD;
        }

        return $this->attributes->get(Repository::HTML_POSITION);
    }

    public function getPriority(): int
    {
        if (!$this->attributes->has(Repository::HTML_PRIORITY)) {
            return 100;
        }

        return $this->attributes->get(Repository::HTML_PRIORITY);
    }
}
