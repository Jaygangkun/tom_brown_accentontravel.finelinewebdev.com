<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:24 */

namespace XTP_BUILD\Http\Message\Authentication;

use XTP_BUILD\Http\Message\Authentication;
use XTP_BUILD\Psr\Http\Message\RequestInterface;

/**
 * Authenticate a PSR-7 Request using a token.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class Bearer implements Authentication
{
    /**
     * @var string
     */
    private $token;

    /**
     * @param string $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * {@inheritdoc}
     */
    public function authenticate(RequestInterface $request)
    {
        $header = sprintf('Bearer %s', $this->token);

        return $request->withHeader('Authorization', $header);
    }
}
