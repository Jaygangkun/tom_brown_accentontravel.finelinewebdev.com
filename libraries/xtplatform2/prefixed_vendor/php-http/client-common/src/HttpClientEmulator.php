<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:34 */

namespace XTP_BUILD\Http\Client\Common;

use XTP_BUILD\Psr\Http\Message\RequestInterface;

/**
 * Emulates an HTTP Client in an HTTP Async Client.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
trait HttpClientEmulator
{
    /**
     * {@inheritdoc}
     *
     * @see HttpClient::sendRequest
     */
    public function sendRequest(RequestInterface $request)
    {
        $promise = $this->sendAsyncRequest($request);

        return $promise->wait();
    }

    /**
     * {@inheritdoc}
     *
     * @see HttpAsyncClient::sendAsyncRequest
     */
    abstract public function sendAsyncRequest(RequestInterface $request);
}
