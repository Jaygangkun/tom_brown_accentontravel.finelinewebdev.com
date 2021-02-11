<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Platform" on 2019-09-01 09:26:09 */

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

interface PluginInterface
{
    public function triggerAfterCreation($item);

    public function triggerReadPolling($afterDate);

    public function triggerAfterUpdate($item);

    public function triggerAfterRemoval($item);

    public function loadUnit(PlugableInterface $extendedUnit);

    public function getExtendedData(PlugableInterface $extendedUnit);

    public function getFieldValue($refId, $fieldName);

    public function getNamedObject($objectName, $id);

    public function onAfterSave($method, $context, $content, $isNew);
}
