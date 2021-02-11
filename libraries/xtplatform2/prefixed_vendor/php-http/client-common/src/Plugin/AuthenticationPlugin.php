<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:31 */

namespace XTP_BUILD\Http\Client\Common\Plugin;

use XTP_BUILD\Http\Client\Common\Plugin;
use XTP_BUILD\Http\Message\Authentication;
use XTP_BUILD\Psr\Http\Message\RequestInterface;

/**
 * Send an authenticated request.
 *
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 */
final class AuthenticationPlugin implements Plugin
{
    /**
     * @var Authentication An authentication system
     */
    private $authentication;

    /**
     * @param Authentication $authentication
     */
    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    /**
     * {@inheritdoc}
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first)
    {
        $request = $this->authentication->authenticate($request);

        return $next($request);
    }
}
