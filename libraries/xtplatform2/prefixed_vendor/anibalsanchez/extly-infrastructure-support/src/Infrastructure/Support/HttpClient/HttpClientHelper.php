<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Platform" on 2019-09-01 09:26:00 */

/*
 * @package     Extly Infrastructure Support
 *
 * @author      Extly, CB. <team@extly.com>
 * @copyright   Copyright (c)2007-2019 Extly, CB. All rights reserved.
 * @license     http://www.opensource.org/licenses/mit-license.html  MIT License
 *
 * @see         https://www.extly.com
 */

namespace XTP_BUILD\Extly\Infrastructure\Support\HttpClient;

use XTP_BUILD\Extly\Infrastructure\Creator\CreatorTrait;
use XTP_BUILD\Extly\Infrastructure\Support\SupportException;
use XTP_BUILD\Http\Client\Common\HttpMethodsClient;
use XTP_BUILD\Http\Client\Common\Plugin\RedirectPlugin;
use XTP_BUILD\Http\Client\Common\Plugin\RetryPlugin;
use XTP_BUILD\Http\Client\Common\PluginClient;
use XTP_BUILD\Http\Discovery\HttpClientDiscovery;
use XTP_BUILD\Http\Discovery\MessageFactoryDiscovery;

class HttpClientHelper
{
    use CreatorTrait;

    public function get($uri, $solveRedirection = true)
    {
        if ($solveRedirection) {
            $response = $this->redirectGet($uri);
        } else {
            $response = $this->rawHttpGet($uri);
        }

        return $this->checkResponse($response);
    }

    public function getLocationHeader($response)
    {
        if ($response->hasHeader('Location')) {
            return $response->getHeader('Location');
        }

        return null;
    }

    public function isOk($response)
    {
        $httpStatusCode = $response->getStatusCode();

        return StatusCodeEnum::HTTP_STATUS_OK === $httpStatusCode;
    }

    public function isRedirection($response)
    {
        $httpStatusCode = $response->getStatusCode();

        return ($httpStatusCode >= StatusCodeEnum::HTTP_STATUS_MOVED_PERMANENTLY)
            && ($httpStatusCode <= StatusCodeEnum::HTTP_STATUS_PERMANENT_REDIRECT);
    }

    public function rawHttpGet($uri)
    {
        $client = new HttpMethodsClient(
            HttpClientDiscovery::find(),
            MessageFactoryDiscovery::find()
        );

        return $client->get($uri);
    }

    protected function redirectGet($uri)
    {
        $retryPlugin = new RetryPlugin();
        $redirectPlugin = new RedirectPlugin();

        $pluginClient = new PluginClient(
            HttpClientDiscovery::find(),
            [
                $retryPlugin,
                $redirectPlugin,
            ]
        );

        $request = MessageFactoryDiscovery::find()->createRequest(RequestMethodEnum::GET, $uri);

        return $pluginClient->sendRequest($request);
    }

    protected function checkResponse($response)
    {
        if ($this->isOk($response)) {
            return $response;
        }

        $httpStatusCode = $response->getStatusCode();

        throw new SupportException(StatusCodeEnum::search($httpStatusCode), $httpStatusCode);
    }
}
