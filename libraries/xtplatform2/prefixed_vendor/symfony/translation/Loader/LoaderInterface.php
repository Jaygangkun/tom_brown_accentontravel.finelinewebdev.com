<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:21:16 */

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace XTP_BUILD\Symfony\Component\Translation\Loader;

use XTP_BUILD\Symfony\Component\Translation\Exception\InvalidResourceException;
use XTP_BUILD\Symfony\Component\Translation\Exception\NotFoundResourceException;
use XTP_BUILD\Symfony\Component\Translation\MessageCatalogue;

/**
 * LoaderInterface is the interface implemented by all translation loaders.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
interface LoaderInterface
{
    /**
     * Loads a locale.
     *
     * @param mixed  $resource A resource
     * @param string $locale   A locale
     * @param string $domain   The domain
     *
     * @return MessageCatalogue A MessageCatalogue instance
     *
     * @throws NotFoundResourceException when the resource cannot be found
     * @throws InvalidResourceException  when the resource cannot be loaded
     */
    public function load($resource, $locale, $domain = 'messages');
}
