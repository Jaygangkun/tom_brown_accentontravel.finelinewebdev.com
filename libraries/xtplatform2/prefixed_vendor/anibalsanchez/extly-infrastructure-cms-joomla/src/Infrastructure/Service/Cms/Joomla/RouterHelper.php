<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Platform" on 2019-09-01 09:26:17 */

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

use Exception;
use XTP_BUILD\Extly\Infrastructure\Creator\CreatorTrait;
use XTP_BUILD\Extly\Infrastructure\Support\UrlHelper;
use XTP_BUILD\Illuminate\Support\Facades\Log;
use Joomla\CMS\Component\ComponentHelper as CMSComponentHelper;

class RouterHelper
{
    use CreatorTrait;

    protected $cmsService;

    public function __construct($cmsService)
    {
        $this->cmsService = $cmsService;
    }

    public function calculateSefUrl($rawUrl, $rootUrl = null)
    {
        try {
            $rawUrl = $this->cleanAdministrator($rawUrl);

            return self::calculateSefUrlInternal($rawUrl, $rootUrl);
        } catch (Exception $e) {
            Log::error('RouteHelper, calculateSefUrl: '.$e->getMessage());

            // Let's generate a workaround URL
            if (!$rootUrl) {
                $rootUrl = $this->cmsService->getRootUri();
            }

            $rawUrl = UrlHelper::create()->combine($rootUrl, $rawUrl.'#na');
        }

        Log::warn('RouteHelper, calculateSefUrl NO Sef URL: '.$rawUrl);

        return $rawUrl;
    }

    /**
     * Better implementation to handle multiple menu entry for component (multiple itemids).
     *
     * @param string $compName Param
     * @param array  $needles  Param
     *
     * @return int
     */
    public function findItemid($compName, $needles = [])
    {
        $component = CMSComponentHelper::getComponent($compName);

        if (!isset($component->id)) {
            return null;
        }

        $menu = $this->cmsService->getMenu('site');
        $items = $menu->getItems('component_id', $component->id);

        if (empty($items)) {
            return null;
        }

        $matches = self::calculateMatches($items, $needles);
        asort($matches);
        $keys = array_keys($matches);
        $match = array_pop($keys);

        return (int) $match;
    }

    private function calculateMatches($items, $needles)
    {
        $matches = [];

        foreach ($items as $item) {
            $url = parse_url($item->link);

            // No URL query ?, ignore it
            if (!isset($url['query'])) {
                $matches[$item->id] = 0;

                continue;
            }

            // We have a query
            parse_str($url['query'], $query);
            $matches[$item->id] = self::calculateMatchRatio($item, $query, $needles);
        }

        return $matches;
    }

    private function calculateMatchRatio($item, $query, $needles)
    {
        $match = 0;

        // If we have a language needle and matches the language, +1!
        if ((isset($needles['language'])) && ($item->language === $needles['language'])) {
            ++$match;
            unset($needles['language']);
        }

        // Checking the query vs the defined needles
        foreach ($needles as $needle => $id) {
            if ((isset($query[$needle]))
                    && (($query[$needle] === $id) || ('*' === $id))) {
                ++$match;
            }
        }

        return $match;
    }

    private function calculateSefUrlInternal($rawUrl, $rootUrl = null)
    {
        $urlHelper = UrlHelper::create();

        if (!$rootUrl) {
            $rootUrl = $this->cmsService->getRootUri();
        }

        $baseUrl = $rootUrl;

        // It's a Multilingual Site
        $isMultilingualSite = $this->cmsService->isMultilingualSite();

        // The URL doesn't have the lang parameter
        if ($isMultilingualSite) {
            $currentSefCode = $this->cmsService->getCurrentSefCode();
            $defaultSefCode = $this->cmsService->getDefaultSefCode();

            // We have to add the /pt sef code
            if ($currentSefCode !== $defaultSefCode) {
                $baseUrl = $urlHelper->combine($rootUrl, '/'.$currentSefCode).'/';

                if (false === strpos($rawUrl, '&lang=')) {
                    $rawUrl = $rawUrl.'&lang='.$this->cmsService->getCurrentSefCode();
                }
            }
        }

        $sefQuery = self::getSefQuery($rootUrl, $rawUrl);

        $body = $urlHelper->extractPage($sefQuery);
        $sefUrl = base64_decode($body, true);

        if (!$sefUrl) {
            return null;
        }

        // Let's make it relative
        try {
            if (!empty($sefUrl)) {
                Log::info('RouteHelper, calculateSefUrlInternal: '.$rawUrl.' => '.$sefUrl);

                return $sefUrl;
            }
        } catch (\InvalidArgumentException $e) {
            Log::warn('RouteHelper, calculateSefUrlInternal: '.$e->getMessage());
        }

        Log::warn('RouteHelper, calculateSefUrlInternal NO Sef URL: '.$rawUrl);

        return $rawUrl;
    }

    private function getSefQuery($rootUrl, $rawUrl)
    {
        $sefQuery = 'index.php?option=com_xtdir4alg&task=sefQuery&url='.base64_encode($rawUrl);

        return UrlHelper::create()->combine($rootUrl, $sefQuery);
    }

    private function cleanAdministrator($url)
    {
        if (false === strpos($url, 'administrator/')) {
            return $url;
        }

        $parts = explode('administrator/', $url);

        return array_pop($parts);
    }
}
