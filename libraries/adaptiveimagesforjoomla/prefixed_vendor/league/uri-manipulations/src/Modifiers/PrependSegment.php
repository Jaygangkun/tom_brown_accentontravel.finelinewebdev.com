<?php
/**
 * League.Uri (http://uri.thephpleague.com)
 *
 * @package    League\Uri
 * @subpackage League\Uri\Modifiers
 * @author     Ignace Nyamagana Butera <nyamsprod@gmail.com>
 * @copyright  2016 Ignace Nyamagana Butera
 * @license    https://github.com/thephpleague/uri-manipulations/blob/master/LICENSE (MIT License)
 * @version    1.5.0
 * @link       https://github.com/thephpleague/uri-manipulations
 */
declare(strict_types=1);

namespace XTP_BUILD\League\Uri\Modifiers;

/**
 * Prepend a path to the URI path
 *
 * @package    League\Uri
 * @subpackage League\Uri\Modifiers
 * @author     Ignace Nyamagana Butera <nyamsprod@gmail.com>
 * @since      1.0.0
 */
class PrependSegment implements UriMiddlewareInterface
{
    use PathMiddlewareTrait;
    use UriMiddlewareTrait;

    /**
     * The path to prepend
     *
     * @var string
     */
    protected $segment;

    /**
     * New instance
     *
     * @param string $segment
     */
    public function __construct(string $segment)
    {
        $this->segment = $this->filterSegment($segment)->getContent();
    }

    /**
     * Modify a URI part
     *
     * @param string $str the URI part string representation
     *
     * @return string the modified URI part string representation
     */
    protected function modifyPath(string $str): string
    {
        return (string) $this->filterSegment($str)->prepend($this->segment);
    }
}
