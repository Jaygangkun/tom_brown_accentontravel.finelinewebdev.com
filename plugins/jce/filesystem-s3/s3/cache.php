<?php

/**
 * @package   	JCE
 * @copyright 	Copyright (c) 2009-2017 Ryan Demmer. All rights reserved.
 * @license   	GNU/GPL 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * JCE is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
class WFS3FileSystemCache {
    
    static $cache = null;
    
    public function __construct($config = array()) {
        self::$cache = JCache::getInstance('output', array('defaultgroup' => 'com_jce', 'caching' => true));
        
        return $this;
    }

    /**
     * @param $key
     * @param $name
     * @param $item
     * @return $this
     */
    public function addItem($key, $name, $item) {
        $cache = $this->get();
        
        $key = ltrim($key, "/");

        if (isset($cache[$key])) {
            $cache[$key][$name] = $item;
            $this->set($cache);
        }
        
        return $this;
    }

    public function getItem($key, $item) {
        $cache = $this->get();

        $key = ltrim($key, "/");

        if (isset($cache[$key]) && isset($cache[$key][$item])) {
            return $cache[$key][$item];
        }

        return null;
    }

    /**
     * @param $key
     * @param $old
     * @param $new
     * @return $this
     */
    public function updateItem($key, $old, $new) {
        $cache = $this->get();
        
        $key = ltrim($key, "/");

        if (isset($cache[$key]) && isset($cache[$key][$old])) {
            $name = $new['name'];
            
            $cache[$key][$name] = array_merge($cache[$key][$old], $new);
        
            if ($name !== $old) {
                unset($cache[$key][$old]);
            }
            
            $this->set($cache);
        }
        
        return $this;
    }

    /**
     * @param $key
     * @param $item
     * @return $this
     */
    public function removeItem($key, $item) {
        $cache = $this->get();
        
        $key = ltrim($key, "/");

        if (isset($cache[$key]) && isset($cache[$key][$item])) {
            unset($cache[$key][$item]);
            
            $this->set($cache);
        }
        
        return $this;
    }

    /**
     * @return array|mixed
     */
    public function get($path = null) {
        $data = self::$cache->get('wf_s3_cache');

        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        if (is_array($data)) {
            if ($path && isset($data[$path])) {
                return $data[$path];
            }

            return $data;
        }

        return array();
    }

    public function set($data) {
        if (empty($data)) {            
            self::$cache->remove('wf_s3_cache');

            return $this;
        }

        /*$cache = $this->get();

        if (!empty($cache)) {
            foreach($data as $k => $v) {
                if (empty($cache[$k])) {
                    $cache[$k] = $v;
                } else {
                    $cache[$k] = array_merge($cache[$k], $v);
                }
            }

            $data = $cache;
        }*/
        
        self::$cache->store(json_encode($data), 'wf_s3_cache');
        
        return $this;
    }

    public function clear() {
        $this->set(null);
        
        return $this;
    }
}
