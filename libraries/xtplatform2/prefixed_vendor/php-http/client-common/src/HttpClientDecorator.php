<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:29 */

namespace XTP_BUILD\Http\Client\Common;

use XTP_BUILD\Http\Client\HttpClient;
use XTP_BUILD\Psr\Http\Client\ClientInterface;
use XTP_BUILD\Psr\Http\Message\RequestInterface;

/**
 * Decorates an HTTP Client.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
trait HttpClientDecorator
{
    /**
     * @var HttpClient|ClientInterface
     */
    protected $httpClient;

    /**
     * {@inheritdoc}
     *
     * @see HttpClient::sendRequest
     */
    public function sendRequest(RequestInterface $request)
    {
        return $this->httpClient->sendRequest($request);
    }
}
