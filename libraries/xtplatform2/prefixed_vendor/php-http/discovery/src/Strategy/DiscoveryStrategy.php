<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:35 */

namespace XTP_BUILD\Http\Discovery\Strategy;

use XTP_BUILD\Http\Discovery\Exception\StrategyUnavailableException;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
interface DiscoveryStrategy
{
    /**
     * Find a resource of a specific type.
     *
     * @param string $type
     *
     * @return array The return value is always an array with zero or more elements. Each
     *               element is an array with two keys ['class' => string, 'condition' => mixed].
     *
     * @throws StrategyUnavailableException if we cannot use this strategy.
     */
    public static function getCandidates($type);
}
