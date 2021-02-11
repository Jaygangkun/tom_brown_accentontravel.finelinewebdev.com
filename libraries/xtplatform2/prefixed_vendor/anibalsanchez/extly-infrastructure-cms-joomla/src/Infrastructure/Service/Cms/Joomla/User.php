<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Platform" on 2019-09-01 09:26:17 */

/*
 * @package     Extly Infrastructure Support for Joomla
 *
 * @author      Extly, CB. <team@extly.com>
 * @copyright   Copyright (c)2007-2019 Extly, CB. All rights reserved.
 * @license     http://www.opensource.org/licenses/mit-license.html  MIT License
 *
 * @see         https://www.extly.com
 */

namespace XTP_BUILD\Extly\Infrastructure\Service\Cms\Joomla;

use XTP_BUILD\Extly\Infrastructure\Creator\CreatorTrait;
use XTP_BUILD\Extly\Infrastructure\Service\Cms\Contracts\NamedObjectInterface;
use XTP_BUILD\Extly\Infrastructure\Service\Cms\Contracts\UserInterface;
use XTP_BUILD\Extly\Infrastructure\Service\Facades\Cms;
use Joomla\CMS\Factory as CMSFactory;
use Joomla\CMS\User\User as CMSUser;

class User implements UserInterface, NamedObjectInterface
{
    use CreatorTrait;

    protected $user;

    public function __construct($id = null)
    {
        // $this->user = CMSUser::getInstance($id);
        $this->user = CMSFactory::getUser($id);
    }

    public function getId()
    {
        return $this->user->id;
    }

    public function getUsername()
    {
        return $this->user->username;
    }

    public function getName()
    {
        return $this->user->name;
    }

    public function isGuest()
    {
        return $this->user->guest;
    }

    public function isAdmin()
    {
        if ($this->user->guest) {
            return false;
        }

        return $this->user->authorise('core.manage', 'com_users');
    }

    public function getTimezone()
    {
        $tz = $this->user->getParam('timezone');

        if (!empty($z)) {
            return $tz;
        }

        return CMSFactory::getConfig()->get('offset');
    }

    public function getLanguage()
    {
        if (Cms::isAdmin()) {
            $language = $this->user->getParam('admin_language');
        } else {
            $language = $this->user->getParam('language');
        }

        if (!empty($language)) {
            return $language;
        }

        return Cms::getDefaultLanguageCode();
    }
}
