<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:28 */

namespace XTP_BUILD\Http\Message\Encoding;

use XTP_BUILD\Clue\StreamFilter as Filter;
use XTP_BUILD\Psr\Http\Message\StreamInterface;

/**
 * Stream deflate (RFC 1951).
 *
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 */
class DeflateStream extends FilteredStream
{
    /**
     * @param StreamInterface $stream
     * @param int             $level
     */
    public function __construct(StreamInterface $stream, $level = -1)
    {
        parent::__construct($stream, ['window' => -15, 'level' => $level]);

        // @deprecated will be removed in 2.0
        $this->writeFilterCallback = Filter\fun($this->writeFilter(), ['window' => -15]);
    }

    /**
     * {@inheritdoc}
     */
    protected function readFilter()
    {
        return 'zlib.deflate';
    }

    /**
     * {@inheritdoc}
     */
    protected function writeFilter()
    {
        return 'zlib.inflate';
    }
}
