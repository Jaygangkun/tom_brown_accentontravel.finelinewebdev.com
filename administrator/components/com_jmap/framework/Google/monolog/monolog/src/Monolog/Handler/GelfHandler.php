<?php declare(strict_types=1);

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Monolog\Handler;

/**
 *
 * @package JMAP::FRAMEWORK::administrator::components::com_jmap
 * @subpackage framework
 * @subpackage google
 * @author Joomla! Extensions Store
 * @copyright (C) 2015 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined ( '_JEXEC' ) or die ();

use Gelf\PublisherInterface;
use Monolog\Logger;
use Monolog\Formatter\GelfMessageFormatter;
use Monolog\Formatter\FormatterInterface;

/**
 * Handler to send messages to a Graylog2 (http://www.graylog2.org) server
 *
 * @author Matt Lehner <mlehner@gmail.com>
 * @author Benjamin Zikarsky <benjamin@zikarsky.de>
 */
class GelfHandler extends AbstractProcessingHandler
{
    /**
     * @var PublisherInterface|null the publisher object that sends the message to the server
     */
    protected $publisher;

    /**
     * @param PublisherInterface $publisher a publisher object
     * @param string|int         $level     The minimum logging level at which this handler will be triggered
     * @param bool               $bubble    Whether the messages that are handled can bubble up the stack or not
     */
    public function __construct(PublisherInterface $publisher, $level = Logger::DEBUG, bool $bubble = true)
    {
        parent::__construct($level, $bubble);

        $this->publisher = $publisher;
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record): void
    {
        $this->publisher->publish($record['formatted']);
    }

    /**
     * {@inheritDoc}
     */
    protected function getDefaultFormatter(): FormatterInterface
    {
        return new GelfMessageFormatter();
    }
}
