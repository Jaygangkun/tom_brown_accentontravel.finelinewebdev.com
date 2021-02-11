<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:29 */

namespace XTP_BUILD\Http\Client\Common\Exception;

use XTP_BUILD\Http\Client\Exception\TransferException;
use XTP_BUILD\Http\Client\Common\BatchResult;

/**
 * This exception is thrown when HttpClient::sendRequests led to at least one failure.
 *
 * It gives access to a BatchResult with the request-exception and request-response pairs.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class BatchException extends TransferException
{
    /**
     * @var BatchResult
     */
    private $result;

    /**
     * @param BatchResult $result
     */
    public function __construct(BatchResult $result)
    {
        $this->result = $result;
    }

    /**
     * Returns the BatchResult that contains all responses and exceptions.
     *
     * @return BatchResult
     */
    public function getResult()
    {
        return $this->result;
    }
}
