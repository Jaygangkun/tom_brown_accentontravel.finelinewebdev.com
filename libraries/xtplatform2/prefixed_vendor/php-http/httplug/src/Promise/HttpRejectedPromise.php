<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:23 */

namespace XTP_BUILD\Http\Client\Promise;

use XTP_BUILD\Http\Client\Exception;
use XTP_BUILD\Http\Promise\Promise;

final class HttpRejectedPromise implements Promise
{
    /**
     * @var Exception
     */
    private $exception;

    /**
     * @param Exception $exception
     */
    public function __construct(Exception $exception)
    {
        $this->exception = $exception;
    }

    /**
     * {@inheritdoc}
     */
    public function then(callable $onFulfilled = null, callable $onRejected = null)
    {
        if (null === $onRejected) {
            return $this;
        }

        try {
            return new HttpFulfilledPromise($onRejected($this->exception));
        } catch (Exception $e) {
            return new self($e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getState()
    {
        return Promise::REJECTED;
    }

    /**
     * {@inheritdoc}
     */
    public function wait($unwrap = true)
    {
        if ($unwrap) {
            throw $this->exception;
        }
    }
}
