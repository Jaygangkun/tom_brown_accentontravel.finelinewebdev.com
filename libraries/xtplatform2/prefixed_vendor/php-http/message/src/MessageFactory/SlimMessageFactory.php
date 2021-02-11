<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:22:28 */

namespace XTP_BUILD\Http\Message\MessageFactory;

use XTP_BUILD\Http\Message\StreamFactory\SlimStreamFactory;
use XTP_BUILD\Http\Message\UriFactory\SlimUriFactory;
use XTP_BUILD\Http\Message\MessageFactory;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Headers;

/**
 * Creates Slim 3 messages.
 *
 * @author Mika Tuupola <tuupola@appelsiini.net>
 */
final class SlimMessageFactory implements MessageFactory
{
    /**
     * @var SlimStreamFactory
     */
    private $streamFactory;

    /**
     * @var SlimUriFactory
     */
    private $uriFactory;

    public function __construct()
    {
        $this->streamFactory = new SlimStreamFactory();
        $this->uriFactory = new SlimUriFactory();
    }

    /**
     * {@inheritdoc}
     */
    public function createRequest(
        $method,
        $uri,
        array $headers = [],
        $body = null,
        $protocolVersion = '1.1'
    ) {
        return (new Request(
            $method,
            $this->uriFactory->createUri($uri),
            new Headers($headers),
            [],
            [],
            $this->streamFactory->createStream($body),
            []
        ))->withProtocolVersion($protocolVersion);
    }

    /**
     * {@inheritdoc}
     */
    public function createResponse(
        $statusCode = 200,
        $reasonPhrase = null,
        array $headers = [],
        $body = null,
        $protocolVersion = '1.1'
    ) {
        return (new Response(
            $statusCode,
            new Headers($headers),
            $this->streamFactory->createStream($body)
        ))->withProtocolVersion($protocolVersion);
    }
}
