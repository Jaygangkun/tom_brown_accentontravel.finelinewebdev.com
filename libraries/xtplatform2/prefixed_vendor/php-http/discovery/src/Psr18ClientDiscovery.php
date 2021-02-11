<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:36 */

namespace XTP_BUILD\Http\Discovery;

use XTP_BUILD\Http\Discovery\Exception\DiscoveryFailedException;
use XTP_BUILD\Psr\Http\Client\ClientInterface;

/**
 * Finds a PSR-18 HTTP Client.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class Psr18ClientDiscovery extends ClassDiscovery
{
    /**
     * Finds a PSR-18 HTTP Client.
     *
     * @return ClientInterface
     *
     * @throws Exception\NotFoundException
     */
    public static function find()
    {
        try {
            $client = static::findOneByType(ClientInterface::class);
        } catch (DiscoveryFailedException $e) {
            throw new \XTP_BUILD\Http\Discovery\Exception\NotFoundException(
                'No PSR-18 clients found. Make sure to install a package providing "psr/http-client-implementation". Example: "php-http/guzzle6-adapter".',
                0,
                $e
            );
        }

        return static::instantiateClass($client);
    }
}
