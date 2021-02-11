<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:34 */

namespace XTP_BUILD\Http\Client\Common;

use XTP_BUILD\Http\Client\HttpAsyncClient;
use XTP_BUILD\Http\Client\HttpClient;

/**
 * Emulates an HTTP client.
 *
 * This should be replaced by an anonymous class in PHP 7.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class EmulatedHttpClient implements HttpClient, HttpAsyncClient
{
    use HttpAsyncClientDecorator;
    use HttpClientEmulator;

    /**
     * @param HttpAsyncClient $httpAsyncClient
     */
    public function __construct(HttpAsyncClient $httpAsyncClient)
    {
        $this->httpAsyncClient = $httpAsyncClient;
    }
}
