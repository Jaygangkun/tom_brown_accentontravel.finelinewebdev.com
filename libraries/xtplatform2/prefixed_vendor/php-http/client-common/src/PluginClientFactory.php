<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:28 */

namespace XTP_BUILD\Http\Client\Common;

use XTP_BUILD\Http\Client\HttpAsyncClient;
use XTP_BUILD\Http\Client\HttpClient;
use XTP_BUILD\Psr\Http\Client\ClientInterface;

/**
 * Factory to create PluginClient instances. Using this factory instead of calling PluginClient constructor will enable
 * the Symfony profiling without any configuration.
 *
 * @author Fabien Bourigault <bourigaultfabien@gmail.com>
 */
final class PluginClientFactory
{
    /**
     * @var callable
     */
    private static $factory;

    /**
     * Set the factory to use.
     * The callable to provide must have the same arguments and return type as PluginClientFactory::createClient.
     * This is used by the HTTPlugBundle to provide a better Symfony integration.
     * Unlike the createClient method, this one is static to allow zero configuration profiling by hooking into early
     * application execution.
     *
     * @internal
     *
     * @param callable $factory
     */
    public static function setFactory(callable $factory)
    {
        static::$factory = $factory;
    }

    /**
     * @param HttpClient|HttpAsyncClient|ClientInterface $client
     * @param Plugin[]                                   $plugins
     * @param array                                      $options {
     *
     *     @var string $client_name to give client a name which may be used when displaying client information  like in
     *         the HTTPlugBundle profiler.
     * }
     *
     * @see PluginClient constructor for PluginClient specific $options.
     *
     * @return PluginClient
     */
    public function createClient($client, array $plugins = [], array $options = [])
    {
        if (static::$factory) {
            $factory = static::$factory;

            return $factory($client, $plugins, $options);
        }

        unset($options['client_name']);

        return new PluginClient($client, $plugins, $options);
    }
}
