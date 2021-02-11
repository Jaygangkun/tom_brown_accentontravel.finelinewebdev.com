<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:23 */

namespace XTP_BUILD\Http\Message\StreamFactory;

use XTP_BUILD\Http\Message\StreamFactory;

/**
 * Creates Guzzle streams.
 *
 * @author Михаил Красильников <m.krasilnikov@yandex.ru>
 */
final class GuzzleStreamFactory implements StreamFactory
{
    /**
     * {@inheritdoc}
     */
    public function createStream($body = null)
    {
        return \XTP_BUILD\GuzzleHttp\Psr7\stream_for($body);
    }
}
