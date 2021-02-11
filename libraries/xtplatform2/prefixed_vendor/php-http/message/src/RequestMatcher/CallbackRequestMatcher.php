<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:26 */

namespace XTP_BUILD\Http\Message\RequestMatcher;

use XTP_BUILD\Http\Message\RequestMatcher;
use XTP_BUILD\Psr\Http\Message\RequestInterface;

/**
 * Match a request with a callback.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class CallbackRequestMatcher implements RequestMatcher
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * {@inheritdoc}
     */
    public function matches(RequestInterface $request)
    {
        return (bool) call_user_func($this->callback, $request);
    }
}
