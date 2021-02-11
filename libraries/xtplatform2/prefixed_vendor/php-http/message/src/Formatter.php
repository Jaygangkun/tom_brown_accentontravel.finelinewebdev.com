<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:26 */

namespace XTP_BUILD\Http\Message;

use XTP_BUILD\Psr\Http\Message\RequestInterface;
use XTP_BUILD\Psr\Http\Message\ResponseInterface;

/**
 * Formats a request and/or a response as a string.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
interface Formatter
{
    /**
     * Formats a request.
     *
     * @param RequestInterface $request
     *
     * @return string
     */
    public function formatRequest(RequestInterface $request);

    /**
     * Formats a response.
     *
     * @param ResponseInterface $response
     *
     * @return string
     */
    public function formatResponse(ResponseInterface $response);
}
