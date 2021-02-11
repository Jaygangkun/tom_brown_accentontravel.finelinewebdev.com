<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Platform" on 2019-09-01 09:26:13 */

/*
 * @package     Extly Infrastructure Support
 *
 * @author      Extly, CB. <team@extly.com>
 * @copyright   Copyright (c)2007-2019 Extly, CB. All rights reserved.
 * @license     http://www.opensource.org/licenses/mit-license.html  MIT License
 *
 * @see         https://www.extly.com
 */

namespace XTP_BUILD\Extly\Infrastructure\Service\Cms\Contracts;

use ArrayAccess;
use XTP_BUILD\MyCLabs\Enum\Enum;

interface CmsServiceInterface
{
    public function boot(ArrayAccess $app);

    public function getName();

    public function getConnectionHost();

    public function getConnectionDatabase();

    public function getConnectionUsername();

    public function getConnectionPassword();

    public function getConnectionPrefix();

    public function translate($value, $default = null);

    public function getSetting($key, $default = null);

    public function getContentManager(Enum $contentType);

    public function getUser($id = null);

    public function getRouter();

    public function getSitename();

    public function getTemporaryFolderPath();

    public function getTemporaryFilename($filename, $ext);

    public function isTemporaryFile($file);

    public function releaseTemporaryFile($file);

    public function getRootFolderPath();

    public function getCacheFolderPath();

    public function getLogFolderPath();

    public function isLocalUrl($url);

    public function convertLocalUrlToFile($url);

    public function getPageLimit();

    public function getWebserviceSecretKey();

    public function getApiToken();

    public function translateLogLevel($cmsLogLevel);

    public function getTimezone();

    public function isMultilingualSite();

    public function getSefCodes();

    public function isAdmin();

    public function getRootUri();
}
