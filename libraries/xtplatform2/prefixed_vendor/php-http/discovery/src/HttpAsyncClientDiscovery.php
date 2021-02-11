<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:34 */

namespace XTP_BUILD\Http\Discovery;

use XTP_BUILD\Http\Client\HttpAsyncClient;
use XTP_BUILD\Http\Discovery\Exception\DiscoveryFailedException;

/**
 * Finds an HTTP Asynchronous Client.
 *
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 */
final class HttpAsyncClientDiscovery extends ClassDiscovery
{
    /**
     * Finds an HTTP Async Client.
     *
     * @return HttpAsyncClient
     *
     * @throws Exception\NotFoundException
     */
    public static function find()
    {
        try {
            $asyncClient = static::findOneByType(HttpAsyncClient::class);
        } catch (DiscoveryFailedException $e) {
            throw new NotFoundException(
                'No HTTPlug async clients found. Make sure to install a package providing "php-http/async-client-implementation". Example: "php-http/guzzle6-adapter".',
                0,
                $e
            );
        }

        return static::instantiateClass($asyncClient);
    }
}
