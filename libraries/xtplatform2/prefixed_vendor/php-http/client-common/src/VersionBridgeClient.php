<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:29 */

namespace XTP_BUILD\Http\Client\Common;

use XTP_BUILD\Psr\Http\Message\RequestInterface;

/**
 * A client that helps you migrate from php-http/httplug 1.x to 2.x. This
 * will also help you to support PHP5 at the same time you support 2.x.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
trait VersionBridgeClient
{
    abstract protected function doSendRequest(RequestInterface $request);

    public function sendRequest(RequestInterface $request)
    {
        return $this->doSendRequest($request);
    }
}
