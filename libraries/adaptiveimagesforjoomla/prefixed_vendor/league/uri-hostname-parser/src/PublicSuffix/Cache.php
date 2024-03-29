<?php
/**
 * League.Uri (http://uri.thephpleague.com)
 *
 * @package    League\Uri
 * @subpackage League\Uri\PublicSuffix
 * @author     Ignace Nyamagana Butera <nyamsprod@gmail.com>
 * @license    https://github.com/thephpleague/uri-hostname-parser/blob/master/LICENSE (MIT License)
 * @version    1.1.1
 * @link       https://github.com/thephpleague/uri-hostname-parser
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace XTP_BUILD\League\Uri\PublicSuffix;

use DateInterval;
use FilesystemIterator;
use Generator;
use XTP_BUILD\Psr\SimpleCache\CacheInterface;
use Traversable;

/**
 * A simple file-based PSR-16 cache implementation.
 *
 * This class is heavily based on the code found in
 *
 * @see https://github.com/kodus/file-cache/blob/master/src/FileCache.php
 */
final class Cache implements CacheInterface
{
    /**
     * @var string control characters for keys, reserved by PSR-16
     */
    const PSR16_RESERVED = '/\{|\}|\(|\)|\/|\\\\|\@|\:/u';
    const FILE_PREFIX = 'leaguepsl-';
    const FILE_EXTENSION = '.cache';
    const CACHE_TTL = 86400 * 7;

    /**
     * @var string
     */
    private $cache_path;

    /**
     * @var int
     */
    private $dir_mode = 0775;

    /**
     * @var int
     */
    private $file_mode = 0664;

    /**
     * @param string $cache_path absolute root path of cache-file folder
     */
    public function __construct(string $cache_path = '')
    {
        if ('' === $cache_path) {
            $cache_path = realpath(dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'data');
        }

        if (!file_exists($cache_path) && file_exists(dirname($cache_path))) {
            $this->mkdir($cache_path); // ensure that the parent path exists
        }

        $this->cache_path = $cache_path;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        $path = $this->getPath($key);
        $expires_at = @filemtime($path);
        if ($expires_at === false) {
            return $default; // file not found
        }

        if (time() >= $expires_at) {
            @unlink($path); // file expired

            return $default;
        }

        $data = @file_get_contents($path);
        if ($data === false) {
            return $default; // race condition: file not found
        }

        if ($data === 'b:0;') {
            return false; // because we can't otherwise distinguish a FALSE return-value from unserialize()
        }

        $value = @unserialize($data);
        if ($value === false) {
            return $default; // unserialize() failed
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $ttl = null)
    {
        if (!is_writable($this->cache_path.DIRECTORY_SEPARATOR)) {
            return false;
        }

        $expires_at = $this->getExpireAt($ttl);
        $path = $this->getPath($key);
        $dir = dirname($path);

        if (!file_exists($dir)) {
            // ensure that the parent path exists:
            $this->mkdir($dir);
        }

        $temp_path = $this->cache_path.DIRECTORY_SEPARATOR.uniqid('', true);
        if (false === @file_put_contents($temp_path, serialize($value))) {
            return false;
        }

        if (false === @chmod($temp_path, $this->file_mode)) {
            return false;
        }

        if (@touch($temp_path, $expires_at) && @rename($temp_path, $path)) {
            return true;
        }

        @unlink($temp_path);

        return false;
    }

    /**
     * Returns the expiration time expressed in the number of seconds since the Unix Epoch.
     *
     * @param mixed $ttl
     *
     * @return int
     */
    private function getExpireAt($ttl): int
    {
        if (is_int($ttl)) {
            return time() + $ttl;
        }

        if ($ttl instanceof DateInterval) {
            return date_create_immutable('@'.time())->add($ttl)->getTimestamp();
        }

        if ($ttl === null) {
            return time() + self::CACHE_TTL;
        }

        throw new CacheException(sprintf('Expected TTL to be an int, a DateInterval or null; received "%s"', is_object($ttl) ? get_class($ttl) : gettype($ttl)));
    }

    /**
     * {@inheritdoc}
     */
    public function delete($key)
    {
        return @unlink($this->getPath($key));
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $success = true;

        $paths = $this->listPaths();
        foreach ($paths as $path) {
            if (!unlink($path)) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * {@inheritdoc}
     */
    public function getMultiple($keys, $default = null)
    {
        if (!is_array($keys) && !$keys instanceof Traversable) {
            throw new CacheException('keys must be either of type array or Traversable');
        }

        $values = [];
        foreach ($keys as $key) {
            $values[$key] = $this->get($key) ?: $default;
        }

        return $values;
    }

    /**
     * {@inheritdoc}
     */
    public function setMultiple($values, $ttl = null)
    {
        if (!is_array($values) && !$values instanceof Traversable) {
            throw new CacheException('keys must be either of type array or Traversable');
        }

        $ok = true;

        foreach ($values as $key => $value) {
            $this->validateKey($key);
            $ok = $this->set($key, $value, $ttl) && $ok;
        }

        return $ok;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteMultiple($keys)
    {
        if (!is_array($keys) && !$keys instanceof Traversable) {
            throw new CacheException('keys must be either of type array or Traversable');
        }

        foreach ($keys as $key) {
            $this->validateKey($key);
            $this->delete($key);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        return $this->get($key, $this) !== $this;
    }

    /**
     * For a given cache key, obtain the absolute file path.
     *
     * @param string $key
     *
     * @throws CacheException if the specified key contains a character reserved by PSR-16
     *
     * @return string absolute path to cache-file
     */
    private function getPath($key): string
    {
        $this->validateKey($key);

        return $this->cache_path.DIRECTORY_SEPARATOR.self::FILE_PREFIX.$key.self::FILE_EXTENSION;
    }

    /**
     * @return Generator|string[]
     */
    private function listPaths(): Generator
    {
        $iterator = new FilesystemIterator(
            $this->cache_path,
            FilesystemIterator::CURRENT_AS_PATHNAME | FilesystemIterator::SKIP_DOTS
        );

        foreach ($iterator as $path) {
            if (!is_dir($path)) {
                yield $path;
            }
        }
    }

    /**
     * @param string $key
     *
     * @throws CacheException
     */
    private function validateKey($key)
    {
        if (!is_string($key)) {
            throw new CacheException(sprintf('Expected key to be a string; received "%s"', is_object($key) ? get_class($key) : gettype($key)));
        }

        if (preg_match(self::PSR16_RESERVED, $key, $match) === 1) {
            throw new CacheException(sprintf('invalid character in key: %s', $match[0]));
        }
    }

    /**
     * Recursively create directories and apply permission mask.
     *
     * @param string $path absolute directory path
     */
    private function mkdir($path)
    {
        $parent_path = dirname($path);

        if (!file_exists($parent_path)) {
            $this->mkdir($parent_path); // recursively create parent dirs first
        }

        mkdir($path);
        chmod($path, $this->dir_mode);
    }
}
