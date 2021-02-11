<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:26 */

namespace XTP_BUILD\Http\Message\RequestMatcher;

use XTP_BUILD\Http\Message\RequestMatcher;
use XTP_BUILD\Psr\Http\Message\RequestInterface;

@trigger_error('The '.__NAMESPACE__.'\RegexRequestMatcher class is deprecated since version 1.2 and will be removed in 2.0. Use Http\Message\RequestMatcher\RequestMatcher instead.', E_USER_DEPRECATED);

/**
 * Match a request with a regex on the uri.
 *
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 *
 * @deprecated since version 1.2 and will be removed in 2.0. Use {@link RequestMatcher} instead.
 */
final class RegexRequestMatcher implements RequestMatcher
{
    /**
     * Matching regex.
     *
     * @var string
     */
    private $regex;

    /**
     * @param string $regex
     */
    public function __construct($regex)
    {
        $this->regex = $regex;
    }

    /**
     * {@inheritdoc}
     */
    public function matches(RequestInterface $request)
    {
        return (bool) preg_match($this->regex, (string) $request->getUri());
    }
}