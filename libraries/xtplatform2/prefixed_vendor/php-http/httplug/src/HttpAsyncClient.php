<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:23 */

namespace XTP_BUILD\Http\Client;

use XTP_BUILD\Http\Promise\Promise;
use XTP_BUILD\Psr\Http\Message\RequestInterface;

/**
 * Sends a PSR-7 Request in an asynchronous way by returning a Promise.
 *
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 */
interface HttpAsyncClient
{
    /**
     * Sends a PSR-7 request in an asynchronous way.
     *
     * Exceptions related to processing the request are available from the returned Promise.
     *
     * @param RequestInterface $request
     *
     * @return Promise Resolves a PSR-7 Response or fails with an Http\Client\Exception.
     *
     * @throws \Exception If processing the request is impossible (eg. bad configuration).
     */
    public function sendAsyncRequest(RequestInterface $request);
}
