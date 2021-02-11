<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:22 */

namespace XTP_BUILD\Http\Client\Exception;

use XTP_BUILD\Psr\Http\Message\RequestInterface;

/**
 * Exception for when a request failed, providing access to the failed request.
 *
 * This could be due to an invalid request, or one of the extending exceptions
 * for network errors or HTTP error responses.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class RequestException extends TransferException
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @param string           $message
     * @param RequestInterface $request
     * @param \Exception|null  $previous
     */
    public function __construct($message, RequestInterface $request, \Exception $previous = null)
    {
        $this->request = $request;

        parent::__construct($message, 0, $previous);
    }

    /**
     * Returns the request.
     *
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }
}
