<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:36 */

namespace XTP_BUILD\Http\Message;

use XTP_BUILD\Psr\Http\Message\ResponseInterface;
use XTP_BUILD\Psr\Http\Message\StreamInterface;

/**
 * Factory for PSR-7 Response.
 *
 * This factory contract can be reused in Message and Server Message factories.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
interface ResponseFactory
{
    /**
     * Creates a new PSR-7 response.
     *
     * @param int                                  $statusCode
     * @param string|null                          $reasonPhrase
     * @param array                                $headers
     * @param resource|string|StreamInterface|null $body
     * @param string                               $protocolVersion
     *
     * @return ResponseInterface
     */
    public function createResponse(
        $statusCode = 200,
        $reasonPhrase = null,
        array $headers = [],
        $body = null,
        $protocolVersion = '1.1'
    );
}
