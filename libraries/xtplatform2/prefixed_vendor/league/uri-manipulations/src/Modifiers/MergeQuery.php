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
 * Add or Update the Query string from the URI object
 *
 * @package    League\Uri
 * @subpackage League\Uri\Modifiers
 * @author     Ignace Nyamagana Butera <nyamsprod@gmail.com>
 * @since      1.0.0
 */
class MergeQuery implements UriMiddlewareInterface
{
    use QueryMiddlewareTrait;
    use UriMiddlewareTrait;

    /**
     * The query to merge
     *
     * @var string
     */
    protected $query;

    /**
     * New Instance
     *
     * @param string $query
     */
    public function __construct(string $query)
    {
        $this->query = (string) $this->filterQuery($query);
    }

    /**
     * Modify a URI part
     *
     * @param string $str the URI part string representation
     *
     * @return string the modified URI part string representation
     */
    protected function modifyQuery(string $str): string
    {
        return (string) $this->filterQuery($str)->merge($this->query);
    }
}
