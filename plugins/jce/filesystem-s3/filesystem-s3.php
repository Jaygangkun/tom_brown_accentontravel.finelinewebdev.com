<?php

/**
 * @package      JCE
 * @copyright    Copyright (C) 2005 - 2013 Ryan Demmer. All rights reserved.
 * @author        Ryan Demmer
 * @license      GNU/GPL
 * JCE is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
// no direct access
defined('_JEXEC') or die('ERROR_403');

require_once(dirname(__FILE__) . '/s3/s3.php');
require_once(dirname(__FILE__) . '/s3/cache.php');

class WFS3FileSystem extends WFFileSystem
{

    protected $instance;

    protected $cache;

    /**
     * Gets the environment's HOME directory if available.
     *
     * @return null|string
     */
    private static function getHomeDir()
    {
        // On Linux/Unix-like systems, use the HOME environment variable
        if ($homeDir = getenv('HOME')) {
            return $homeDir;
        }

        // Get the HOMEDRIVE and HOMEPATH values for Windows hosts
        $homeDrive = getenv('HOMEDRIVE');
        $homePath = getenv('HOMEPATH');

        return ($homeDrive && $homePath) ? $homeDrive . $homePath : null;
    }

    private function getCredentials()
    {
        $home = self::getHomeDir();
        
        $wf = WFEditorPlugin::getInstance();

        if (is_readable($home . '/.aws/credentials')) {
            $data = parse_ini_file($home . '/.aws/credentials', true);

            if ($data !== false) {
                // get profile
                $profile = $wf->getParam('filesystem.s3.credentials_profile', 'default');

                if (is_array($data) && !empty($data[$profile])) {
                    $key = isset($data[$profile]['aws_access_key_id']) ? $data[$profile]['aws_access_key_id'] : '';
                    $secret = isset($data[$profile]['aws_secret_access_key']) ? $data[$profile]['aws_secret_access_key'] : '';

                    return array('key' => $key, 'secret' => $secret);
                }
            }
        }

        // try environment variables
        $key    = getenv('AWS_ACCESS_KEY_ID');
        $secret = getenv('AWS_SECRET_ACCESS_KEY');
        
        // try parameters
        if (!$key || !$secret) {
            $key      = $wf->getParam('filesystem.s3.accesskey', '');
            $secret   = $wf->getParam('filesystem.s3.secretkey', '');
        }

        return array('key' => $key, 'secret' => $secret);
    }

    /**
     * Constructor activating the default information of the class
     *
     * @access    protected
     */
    public function __construct($config = array())
    {
        parent::__construct($config);
        
        $wf = WFEditorPlugin::getInstance();

        $this->bucket = $wf->getParam('filesystem.s3.bucket', '');
        $this->cname = $wf->getParam('filesystem.s3.cname', $this->bucket);
        $this->timeout = (int)$wf->getParam('filesystem.s3.timeout', 3600);
        $this->ssl = (int)$wf->getParam('filesystem.s3.ssl', 1);
        $this->endpoint = $wf->getParam('filesystem.s3.endpoint', 's3.amazonaws.com');
        
        $this->acl = $wf->getParam('filesystem.s3.acl', 'private');

        // get credentials
        $credentials = $this->getCredentials();

        // create instance
        $this->instance = new S3($credentials["key"], $credentials["secret"], (bool)$this->ssl, $this->endpoint);

        S3::setExceptions(true);

        $elements = array(
            's3_acl' => array(
                'label' => WFText::_('PLG_JCE_FILESYSTEM_S3_ACL', 'ACL'),
                'options' => array(
                    'private' => WFText::_('PLG_JCE_FILESYSTEM_S3_ACL_PRIVATE', 'Private'),
                    'public-read' => WFText::_('PLG_JCE_FILESYSTEM_S3_ACL_PUBLIC_READ', 'Public Read'),
                    'public-read-write' => WFText::_('PLG_JCE_FILESYSTEM_S3_ACL_PUBLIC_READ_WRITE', 'Public Read/Write'),
                    'authenticated-read' => WFText::_('PLG_JCE_FILESYSTEM_S3_ACL_AUTHENTICATED_READ', 'Authenticated Read')
                ),
                'default' => $this->acl
            )
        );

        $config = array(
            'local' => false,
            'upload' => array(
                'unique_filenames' => false,
                'elements' => $elements
            ),
            'folder_new' => array(
                'elements' => $elements
            ),
            'base' => $this->cname === $this->bucket ? $this->endpoint . '/' . $this->bucket : $this->cname
        );

        $this->setProperties($config);

        $this->cache = new WFS3FileSystemCache();
    }

    /**
     * Get the base directory.
     * @return string base dir
     */
    public function getBaseDir()
    {
        return $this->getRootDir();
    }

    /**
     * Get the full base url
     * @return string base url
     */
    public function getBaseURL()
    {
        return $this->getRootDir();
    }

    /**
     * Return the full user directory path. Create if required
     *
     * @param string    The base path
     * @access public
     * @return Full path to folder
     */
    public function getRootDir()
    {
        static $root;

        if (!isset($root)) {
            $root = parent::getRootDir();
            $wf = WFEditorPlugin::getInstance();

            if (empty($root) && !$wf->getParam('filesystem.s3.allow_root', 0)) {
                $root = 'images';
            }

            if (!empty($root) || !$this->exists($root)) {
                if ($this->folderCreate($root)) {
                    return $root;
                }
            }
        }

        return $root;
    }

    /**
     * Count the number of files in a folder
     * @return integer File total
     * @param string $path Absolute path to folder
     */
    public function countItems($relative)
    {
        return $this->countFiles($relative);
    }

    public function getTotalSize($relative, $recurse = true)
    {
        $total = 0;

        $list = $this->getItems($relative);

        if (!empty($list)) {
            foreach ($list as $id => $value) {
                if (substr($value['name'], -1) !== "/") {
                    $total += (int)$value['size'];

                    if ($recurse) {
                        $total += $this->getTotalSize($id, $recurse);
                    }
                }
            }
        }

        return $total;
    }

    /**
     * Count the number of files in a given folder
     * @return integer Total number of folders
     * @param string $path Absolute path to folder
     */
    public function countFiles($relative, $recurse = false)
    {
        $total = 0;

        $list = $this->getItems($relative);

        if (!empty($list)) {
            foreach ($list as $id => $value) {
                // not a folder
                if (array_key_exists("prefix", $value) === false && basename($id) !== "index.html") {
                    $total++;

                    if ($recurse) {
                        $total += $this->countFiles($id, $recurse);
                    }
                }
            }
        }

        return $total;
    }

    /**
     * Determine whether a key exists
     * @return boolean
     */
    public function exists($path)
    {
        $path = trim($path, "/");

        if ($this->is_dir($path)) {
            $path .= "/";
        }

        return @$this->instance->getObjectInfo($this->bucket, $path, false);
    }

    public function getFolders($relative, $filter = '')
    {
        $folders = array();
        $list = $this->getItems($relative);

        if (!empty($list)) {
            natcasesort($list);
            foreach ($list as $id => $value) {

                // check for folder prefix
                if (array_key_exists("prefix", $value)) {

                    // filter
                    if ($filter && preg_match('#' . $filter . '#', $id) === false) {
                        continue;
                    }

                    // get name
                    $item = basename($id);
                    // utf-8 encode
                    $item = WFUtility::isUTF8($item) ? $item : utf8_encode($item);

                    $data = array(
                        'id' => WFUtility::makePath($relative, $item, '/'),
                        'name' => $item,
                        'writable' => true,
                        'type' => 'folders'
                    );

                    $properties = self::getFolderDetails($id, $value);

                    $folders[] = array_merge($data, array('properties' => $properties));
                }
            }
        }

        return $folders;
    }

    private function createURL($path)
    {
        static $url = array();

        if (empty($url[$path])) {
            $host = $this->ssl ? 'https://' : 'http://';
            $base = $this->bucket . '.' . $this->endpoint;

            if ($this->cname && $this->cname !== $this->bucket) {
                $base = $this->cname;
            }

            // create public url
            $url[$path] = $host . WFUtility::makePath($base, $path);

            // check ACL
            $acl = $this->instance->getAccessControlPolicy($this->bucket, trim($path, '/'));

            $public = false;

            if (!empty($acl['acl'])) {
                foreach ($acl['acl'] as $rule) {

                    if ($rule['permission'] === "READ" && (!empty($rule['uri']) && $rule['uri'] === "http://acs.amazonaws.com/groups/global/AllUsers")) {
                        $public = true;
                        break;
                    }
                }
            }

            // get authenticated url if not public
            if (!$public) {
                $url[$path] = $this->instance->getAuthenticatedURL($this->cname, trim($path, '/'), $this->timeout);
            }
        }
        
        return $url[$path];
    }

    public function getFiles($relative, $filter)
    {
        $files = array();

        $list = $this->getItems($relative);

        $x = 1;

        if (!empty($list)) {
            // Sort alphabetically
            natcasesort($list);
            foreach ($list as $key => $value) {
                // is a folder, skip
                if (array_key_exists("prefix", $value)) {
                    continue;
                }

                // not a valid file
                if ($this->is_file($key) === false) {
                    continue;
                }

                // doesn't match filter, skip
                if ($filter && preg_match('#' . $filter . '#', $key) === false) {
                    continue;
                }

                // create name
                $item = basename($key);
                // encode
                $item = WFUtility::isUTF8($item) ? $item : utf8_encode($item);

                // create relative file
                $id = WFUtility::makePath($relative, $item, '/');

                // remove leading slash
                $id = ltrim($id, '/');

                $data = array(
                    'id' => $id,
                    'url' => '',
                    'name' => $item,
                    'writable' => true,
                    'type' => 'files'
                );

                $properties = self::getFileDetails($id, $x, $value, true);

                $files[] = array_merge($data, array('properties' => $properties));

                $x++;
            }
        }

        return $files;
    }

    protected function getItems($relative)
    {
        $path = WFUtility::makePath($this->getRootDir(), $relative);
        $path = trim($path, "/");

        if (JRequest::getInt('refresh')) {
            $this->cache->clear();
        }

        $items = $this->cache->get();

        // add slash
        $path .= "/";

        if (empty($items[$path])) {
            $items[$path] = (array)$this->instance->getBucket($this->bucket, $path, null, null, '/', true);
            $this->cache->set($items);
        }

        return $items[$path];
    }

    /**
     * Get a folders properties
     *
     * @return array Array of properties
     * @param string $dir Folder relative path
     */
    public function getFolderDetails($path, $info = array())
    {
        $info = $this->cache->getItem(dirname($path) . "/", $path);

        if (!empty($info['time'])) {
            $path = trim($path, "/");
            $info = $this->instance->getObjectInfo($this->bucket, $path . "/");
        }
        return array('modified' => strftime($info['time']));
    }

    /**
     * Get a files properties
     *
     * @return array Array of properties
     * @param string $file File relative path
     */
    public function getFileDetails($relative, $count = 1, $info = array(), $createURL = true)
    {
        $file = WFUtility::makePath($this->getRootDir(), $relative);
        $path = dirname($file);

        $path  = trim($path, "/");
        $info  = $this->cache->getItem($path . "/", $file);

        if (empty($info['time']) && empty($info['size'])) {
            $info = @$this->instance->getObjectInfo($this->bucket, trim($file, "/"));
            $this->cache->updateItem($path, $file, $info);
        }

        $data = array(
            'size'      => !empty($info['size']) ? $info['size'] : '',
            'modified'  => !empty($info['time']) ? strftime($info['time']) : ''
        );
        
        if ($createURL) {
            if (empty($info['url'])) {
                $info['url'] = $this->createURL($file);
            }

            $data['url'] = $info['url'];

            $this->cache->updateItem($path . "/", $file, $info);
        }

        if (preg_match('/\.(jpeg|jpg|gif|png)/i', $file)) {
            $data['preview'] = $data['url'];
        }

        return $data;
    }

    /**
     * Delete the relative file(s).
     * @param $files the relative path to the file name or comma seperated list of multiple paths.
     * @return string $error on failure.
     */
    public function delete($src, $recursive = false)
    {
        // get error class
        $result = new WFFileSystemResult();
        $path = WFUtility::makePath($this->getBaseDir(), $src);
        $path = trim($path, "/");

        if ($this->is_file($path)) {
            $result->type = 'files';
            $result->state = $this->instance->deleteObject($this->bucket, $path);
        } else {
            $result->type = 'folders';

            if ($recursive == false && $this->countItems($src) > 0) {
                $result->message = JText::sprintf('WF_MANAGER_FOLDER_NOT_EMPTY', basename($path));
            } else {
                if ($this->deleteItems($path)) {
                    $result->state = $this->instance->deleteObject($this->bucket, $path . "/");
                }
            }
        }

        // update cache
        if ($result->state) {
            $this->cache->removeItem(dirname($path) . '/', $path);
        }

        return $result;
    }

    /**
     * Recursively delete files and folders in the specified path
     * @param $path
     * @return bool
     */
    protected function deleteItems($path)
    {
        $items = $this->getItems($path);

        $return = false;

        foreach ($items as $item => $value) {
            $item = trim($item, "/");

            // folder
            if (array_key_exists('prefix', $value)) {
                $return = $this->deleteItems($item . "/");
            } else {
                // file
                $return = $this->instance->deleteObject($this->bucket, $item);
            }
        }

        return $return;
    }

    protected function copyItems($src, $destination, $delete = false)
    {
        $items = $this->getItems($src);

        if (!empty($items)) {
            foreach ($items as $item => $value) {
                $dest = WFUtility::makePath($destination, basename($item));
                $dest = trim($dest, "/");

                $state = $this->instance->copyObject($this->bucket, $item, $this->bucket, $dest);
                // remove original
                if ($state && $delete) {
                    $this->instance->deleteObject($this->bucket, $item);
                }
            }
        }

        return true;
    }

    /**
     * Rename a file.
     * @param string $src The relative path of the source file
     * @param string $name The name of the new file
     * @return string $error
     */
    public function rename($src, $name)
    {

        $src = WFUtility::makePath($this->getBaseDir(), rawurldecode($src));
        $src = trim($src, "/");

        $path = pathinfo($src);
        $dir = $path['dirname'];

        $result = new WFFileSystemResult();

        if ($this->is_file($src)) {
            $ext = $path['extension'];
            $file = $name . '.' . $ext;
            $dest = WFUtility::makePath($dir, $file);

            if ($this->exists($dest)) {
                return $result;
            }

            if ($resp = $this->instance->copyObject($this->bucket, $src, $this->bucket, $dest)) {
                $result->state = $this->instance->deleteObject($this->bucket, $src);
                $result->path = $dest;

                // update cache
                if ($result->state) {
                    // create new item
                    $new = array_merge($resp, array('name' => $dest, 'url' => $this->createURL($dest)));
                    // update cache
                    $this->cache->updateItem($dir . "/", $src, $new);
                }
            }

            $result->type = 'files';
        } else {
            $dest = WFUtility::makePath($dir, $name);

            if ($this->exists($dest)) {
                return $result;
            }

            // create new folder, copy files
            $result->state = $this->copyItems($src, $dest . "/", true);
            $result->type = 'folders';
        }

        return $result;
    }

    /**
     * Copy a file.
     * @param string $files The relative file or comma seperated list of files
     * @param string $dest The relative path of the destination dir
     * @return string $error on failure
     */
    public function copy($file, $destination, $delete = false)
    {
        $result = new WFFileSystemResult();

        $src = WFUtility::makePath($this->getBaseDir(), $file);
        $dest = WFUtility::makePath($this->getBaseDir(), WFUtility::makePath($destination, basename($file)));

        // src is a file
        if ($this->is_file($src)) {
            $result->type = 'files';

            $result->state = $this->instance->copyObject($this->bucket, $src, $this->bucket, $dest);

            if ($result->state && $delete) {
                $this->instance->deleteObject($this->bucket, $src);
            }

            $result->path = $dest;
        } else {
            // Folders cannot be copied into themselves as this creates an infinite copy / paste loop
            if ($file === $destination) {
                $result->state = false;
                $result->message = WFText::_('WF_MANAGER_COPY_INTO_ERROR');

                return $result;
            }
            $result->state = $this->copyItems($src, $dest, $delete);
            $result->type = 'folders';
        }

        return $result;
    }

    /**
     * Copy a file.
     * @param string $files The relative file or comma seperated list of files
     * @param string $dest The relative path of the destination dir
     * @return string $error on failure
     */
    public function move($file, $dest)
    {
        return $this->copy($file, $dest, true);
    }

    /**
     * New folder base function. A wrapper for the JFolder::create function
     * @param string $folder The folder to create
     * @return boolean true on success
     */
    public function folderCreate($folder, $acl = 'private')
    {
        $input = '<html><body bgcolor="#FFFFFF"></body></html>';
        return $this->instance->putObject($input, $this->bucket, $folder . '/index.html', $acl);
    }

    /**
     * New folder
     * @param string $dir The base dir
     * @param string $new_dir The folder to be created
     * @return string $error on failure
     */
    public function createFolder($dir, $new)
    {
        $path = WFUtility::makePath($this->getBaseDir(), rawurldecode($dir));
        $folder = WFUtility::makePath($path, WFUtility::makeSafe($new));

        $acl = JRequest::getCmd('s3_acl', $this->acl);
        
        $result = new WFFileSystemResult();
        $result->state = $this->folderCreate($folder, $acl);

        // clear cache
        if ($result->state) {
            $data = $this->getFolderDetails($folder);

            $item = array_merge(array('name' => $folder, $data));

            $this->cache->addItem($path . "/", $dir, $item);
        }

        return $result;
    }

    public function getDimensions($path)
    {
        $path = WFUtility::makePath($this->getRootDir(), rawurldecode($path));
        $url = $this->createURL($path);

        $width = 0;
        $height = 0;

        // try getimagesize (may not work on some systems)
        $dim = @getimagesize($url);

        if (!empty($dim)) {
            list($width, $height) = $dim;
        }

        return array(
            'width' => $width,
            'height' => $height
        );
    }

    public function upload($method, $src, $dir, $name, $chunks = 1, $chunk = 0)
    {
        $path = WFUtility::makePath($this->getRootDir(), rawurldecode($dir));
        $dest = WFUtility::makePath($path, WFUtility::makeSafe($name));

        $result = new WFFileSystemResult();
        $acl 	= JRequest::getCmd('s3_acl', $this->acl);

        $input = $this->instance->inputFile($src);

        $result->state = $this->instance->putObject($input, $this->bucket, $dest, $acl);

        $result->path = $dest;
        $result->url = $this->createURL($dest);

        // clear cache
        if ($result->state) {
            $this->cache->clear();
        }

        return $result;
    }

    public function read($file)
    {
        $path = WFUtility::makePath($this->getBaseDir(), rawurldecode($file));
        $path = trim($path, "/");

        return $this->instance->inputFile($this->bucket, $path);
    }

    public function write($file, $content)
    {
        $path = WFUtility::makePath($this->getBaseDir(), $file);
        $path = trim($path, "/");

        return $this->instance->putObject($content, $this->bucket, $path);
    }

    /**
     * Get the source directory of a file path
     */
    public function getSourceDir($path)
    {
        if (!empty($path)) {
            // directory path relative to base dir
            if ($this->is_file($path)) {
                $path = mb_substr(dirname($path), mb_strlen($this->getRootDir()));
            }
        }

        return $path;
    }

    public function isMatch($needle, $haystack)
    {
        $needle = parse_url($needle, PHP_URL_PATH);
        $haystack = WFUtility::makePath($this->cname, $haystack);

        return trim($haystack, '/') === ltrim($needle, '/');
    }

    public function pathinfo($path)
    {
        $path = parse_url($path, PHP_URL_PATH);
        return pathinfo($path);
    }

    public function is_file($path)
    {
        $filetypes = $this->get('filetypes');
        
        if (is_string($filetypes)) {
        	$filetypes = explode(',', $filetypes);
        }
        
        return (bool) preg_match('#\.(' . implode('|', $filetypes) . ')$#i', basename($path));
    }

    public function is_dir($path)
    {
        return $this->is_file($path) === false;
    }
}
