<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:30 */

namespace XTP_BUILD\Http\Client\Common\HttpClientPool;

use XTP_BUILD\Http\Client\Common\Exception\HttpClientNotFoundException;
use XTP_BUILD\Http\Client\Common\HttpClientPool;

/**
 * RoundRobinClientPool will choose the next client in the pool.
 *
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 */
final class RoundRobinClientPool extends HttpClientPool
{
    /**
     * {@inheritdoc}
     */
    protected function chooseHttpClient()
    {
        $last = current($this->clientPool);

        do {
            $client = next($this->clientPool);

            if (false === $client) {
                $client = reset($this->clientPool);

                if (false === $client) {
                    throw new HttpClientNotFoundException('Cannot choose a http client as there is no one present in the pool');
                }
            }

            // Case when there is only one and the last one has been disabled
            if ($last === $client && $client->isDisabled()) {
                throw new HttpClientNotFoundException('Cannot choose a http client as there is no one enabled in the pool');
            }
        } while ($client->isDisabled());

        return $client;
    }
}
