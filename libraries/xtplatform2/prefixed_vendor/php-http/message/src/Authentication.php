<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:23 */

namespace XTP_BUILD\Http\Message;

use XTP_BUILD\Psr\Http\Message\RequestInterface;

/**
 * Add authentication information to a PSR-7 Request.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
interface Authentication
{
    /**
     * Alter the request to add the authentication credentials.
     *
     * To do that, the implementation might use pre-stored credentials or do
     * separate HTTP requests to obtain a valid token.
     *
     * @param RequestInterface $request The request without authentication information
     *
     * @return RequestInterface The request with added authentication information
     */
    public function authenticate(RequestInterface $request);
}
