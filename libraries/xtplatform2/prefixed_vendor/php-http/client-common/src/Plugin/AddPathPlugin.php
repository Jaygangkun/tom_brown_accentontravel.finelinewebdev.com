<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:31 */

namespace XTP_BUILD\Http\Client\Common\Plugin;

use XTP_BUILD\Http\Client\Common\Plugin;
use XTP_BUILD\Psr\Http\Message\RequestInterface;
use XTP_BUILD\Psr\Http\Message\UriInterface;

/**
 * Prepend a base path to the request URI. Useful for base API URLs like http://domain.com/api.
 *
 * @author Sullivan Senechal <soullivaneuh@gmail.com>
 */
final class AddPathPlugin implements Plugin
{
    /**
     * @var UriInterface
     */
    private $uri;

    /**
     * Stores identifiers of the already altered requests.
     *
     * @var array
     */
    private $alteredRequests = [];

    /**
     * @param UriInterface $uri
     */
    public function __construct(UriInterface $uri)
    {
        if ('' === $uri->getPath()) {
            throw new \LogicException('URI path cannot be empty');
        }

        if ('/' === substr($uri->getPath(), -1)) {
            $uri = $uri->withPath(rtrim($uri->getPath(), '/'));
        }

        $this->uri = $uri;
    }

    /**
     * {@inheritdoc}
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first)
    {
        $identifier = spl_object_hash((object) $first);

        if (!array_key_exists($identifier, $this->alteredRequests)) {
            $request = $request->withUri($request->getUri()
                ->withPath($this->uri->getPath().$request->getUri()->getPath())
            );
            $this->alteredRequests[$identifier] = $identifier;
        }

        return $next($request);
    }
}
