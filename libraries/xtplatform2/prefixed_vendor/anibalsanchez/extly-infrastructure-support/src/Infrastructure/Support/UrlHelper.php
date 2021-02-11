<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Platform" on 2019-09-01 09:26:05 */

/*
 * @package     Extly Infrastructure Support
 *
 * @author      Extly, CB. <team@extly.com>
 * @copyright   Copyright (c)2007-2019 Extly, CB. All rights reserved.
 * @license     http://www.opensource.org/licenses/mit-license.html  MIT License
 *
 * @see         https://www.extly.com
 */

namespace XTP_BUILD\Extly\Infrastructure\Support;

use Exception;
use XTP_BUILD\Extly\Infrastructure\Creator\CreatorTrait;
use XTP_BUILD\Extly\Infrastructure\Support\HttpClient\HttpClientHelper;
use XTP_BUILD\ForceUTF8\Encoding;
use XTP_BUILD\League\Uri\Components\HierarchicalPath;
use XTP_BUILD\League\Uri\Modifiers\Relativize;
use XTP_BUILD\League\Uri\Modifiers\Resolve;
use XTP_BUILD\League\Uri\Schemes\Http as HttpUri;

class UrlHelper
{
    use CreatorTrait;

    const HTTP_PROTOCOL = 'http:';

    public function isAbsoluteUrl($url)
    {
        try {
            $uri = HttpUri::createFromString($url);
            $uriReference = \XTP_BUILD\League\Uri\Modifiers\uri_reference($uri);

            $absoluteUri = $uriReference['absolute_uri'];

            // Full absolute URL
            if ($absoluteUri) {
                return true;
            }

            // Kind of absolute Url //mail.google.com ...
            if (($url[0] === '/') && ($url[1] === '/')) {
                return true;
            }

            return false;
        } catch (Exception $e) {
        }

        // I don't know, so not Absolute
        return false;
    }

    public function absolutizeUrl($url)
    {
        if (empty($url)) {
            return null;
        }

        if (self::isAbsoluteUrl($url)) {
            return $url;
        }

        $url = Estring::create($url);

        if ($url->startsWith('//')) {
            return $url->ensureLeft('http:');
        }

        return $url->ensureLeft('http://');
    }

    public function relativizeAbsUrl($url)
    {
        $uri = HttpUri::createFromString($url);
        $query = $uri->getQuery();

        return 'index.php?'.$query;
    }

    public function relativize($baseUrl, $url)
    {
        $baseUri = HttpUri::createFromString($baseUrl);
        $relativizer = new Relativize($baseUri);
        $uri = HttpUri::createFromString($url);
        $relativeUri = $relativizer($uri);

        return (string) $relativeUri;
    }

    /**
     * detectMimeType.
     *
     * @param string $url Param
     *
     * @return string
     */
    public function detectMimeType($url)
    {
        $url = self::absolutizeUrl($url);
        $response = HttpClientHelper::create()->get($url);

        $contentType = $response->getHeader('Content-Type');
        $mimeType = array_shift($contentType);

        return $mimeType;
    }

    /**
     * download.
     *
     * @param string $url        Param
     * @param string $tempFolder Param
     *
     * @return string
     */
    public function download($url, $tempFolder)
    {
        $url = self::absolutizeUrl($url);
        $response = HttpClientHelper::create()->get($url);

        $contentType = $response->getHeader('Content-Type');
        $mimeType = array_shift($contentType);
        $extension = Estring::create($mimeType)->mimeToExtension();

        $urlUri = HttpUri::createFromString($url);
        $fullPath = $urlUri->getPath();
        $pathinfoFilename = pathinfo($fullPath, PATHINFO_FILENAME);
        $pathinfoExtension = pathinfo($fullPath, PATHINFO_EXTENSION);
        $filename = $pathinfoFilename.'.'.$pathinfoExtension;

        if (empty($pathinfoExtension)) {
            $filename = (string) Estring::create($pathinfoFilename)->ensureRight('.'.$extension);
        }

        $toFile = $tempFolder.\DIRECTORY_SEPARATOR.$filename;

        $file = fopen($toFile, 'a');
        $handle = $response->getBody();

        while (!$handle->eof()) {
            fwrite($file, $handle->read(131072));
        }

        fclose($file);

        return $toFile;
    }

    /**
     * get.
     *
     * @param string $url Params
     *
     * @return string
     */
    public function getRootUrl($url)
    {
        $uri = HttpUri::createFromString($url);
        $path = $uri->getPath();

        if (empty($path)) {
            return $url;
        }

        list($rootUrl) = explode($path, $url);

        return rtrim($rootUrl, '/');
    }

    /**
     * extractPage.
     *
     * @param mixed $url
     *
     * @return string
     */
    public function extractPage($url)
    {
        $response = HttpClientHelper::create()->get($url);
        $page = $response->getBody()->getContents();
        $page = Encoding::toUTF8($page);

        if (empty($page)) {
            throw new SupportException('Unable to retrieve Page.');
        }

        return $page;
    }

    public function combine($urlBase, $relativeUrl)
    {
        // Nothing to do
        if (self::isAbsoluteUrl($relativeUrl)) {
            return $relativeUrl;
        }

        $url = (string) Estring::create(self::absolutizeUrl($urlBase))->ensureRight('/');
        $baseUri = HttpUri::createFromString($url);
        $hierarchicalBasePath = new HierarchicalPath($baseUri->getPath());
        $baseUriSegments = array_filter($hierarchicalBasePath->getSegments());

        $cleanRelUrl = $this->cleanCommonSegments($relativeUrl, $baseUriSegments);
        $preparedRelUrl = (string) Estring::create($cleanRelUrl)
            ->ensureLeft('/')
            ->ensureLeft('.');
        $relativeUri = HttpUri::createFromString($preparedRelUrl);

        $modifier = new Resolve($baseUri);

        return (string) $modifier->process($relativeUri);
    }

    public function getHost($stringUri)
    {
        return HttpUri::createFromString($stringUri)->getHost();
    }

    public function updateHost($url, $host)
    {
        if (!$this->isAbsoluteUrl($host)) {
            $host = $this->absolutizeUrl($host);
        }

        $hostUri = HttpUri::createFromString($host);

        return (string) HttpUri::createFromString($url)
            ->withScheme($hostUri->getScheme())
            ->withHost($hostUri->getHost())
            ->withPort($hostUri->getPort());
    }

    protected function cleanCommonSegments($relativeUrl, $baseUriSegments)
    {
        if (empty($baseUriSegments)) {
            return $relativeUrl;
        }

        $hierarchicalPath = new HierarchicalPath($relativeUrl);
        $segments = $hierarchicalPath->getSegments();

        $segmentsToBeChecked = $segments;
        $position = 0;

        foreach ($segmentsToBeChecked as $segment) {
            if (!isset($baseUriSegments[$position])) {
                break;
            }

            if ($segment !== $baseUriSegments[$position]) {
                break;
            }

            array_shift($segments);
            ++$position;
        }

        return implode('/', $segments);
    }
}
