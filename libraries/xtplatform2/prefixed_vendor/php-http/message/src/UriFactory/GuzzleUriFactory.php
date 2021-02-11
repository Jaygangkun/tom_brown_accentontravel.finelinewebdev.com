<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:28 */

namespace XTP_BUILD\Http\Message\UriFactory;

use XTP_BUILD\GuzzleHttp\Psr7;
use XTP_BUILD\Http\Message\UriFactory;

/**
 * Creates Guzzle URI.
 *
 * @author David de Boer <david@ddeboer.nl>
 */
final class GuzzleUriFactory implements UriFactory
{
    /**
     * {@inheritdoc}
     */
    public function createUri($uri)
    {
        return Psr7\uri_for($uri);
    }
}
