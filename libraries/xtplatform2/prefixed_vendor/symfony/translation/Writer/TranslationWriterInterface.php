<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Libraries" on 2019-08-30 11:21:17 */

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace XTP_BUILD\Symfony\Component\Translation\Writer;

use XTP_BUILD\Symfony\Component\Translation\Exception\InvalidArgumentException;
use XTP_BUILD\Symfony\Component\Translation\MessageCatalogue;

/**
 * TranslationWriter writes translation messages.
 *
 * @author Michel Salib <michelsalib@hotmail.com>
 */
interface TranslationWriterInterface
{
    /**
     * Writes translation from the catalogue according to the selected format.
     *
     * @param MessageCatalogue $catalogue The message catalogue to write
     * @param string           $format    The format to use to dump the messages
     * @param array            $options   Options that are passed to the dumper
     *
     * @throws InvalidArgumentException
     */
    public function write(MessageCatalogue $catalogue, $format, $options = []);
}