<?php /* This file has been prefixed by <PHP-Prefixer> for "XT Search for Algolia" on 2019-08-30 11:27:25 */

/*
 * @package     Extly.Plugins
 * @subpackage  adaptiveimagesforjoomla - XT Adaptive Images
 *
 * @author      Extly, CB. <team@extly.com>
 * @copyright   Copyright (c)2007-2018 Extly, CB. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @link        https://www.extly.com
 */

namespace XTP_BUILD\Extly\AdaptiveImages\Common;

/**
 * SrcsetHelper.
 *
 * @since       1.0
 */
class SrcsetHelper
{
    private $srcsetDictionary;

    private $sizes;

    private $adaptiveImagesHelper;

    public function __construct($adaptiveImagesHelper = null)
    {
        $this->adaptiveImagesHelper = $adaptiveImagesHelper;
    }

    /**
     * generateSrcset.
     *
     * @param array $srcset Param
     *
     * @return string
     */
    public function generateSrcset($srcset)
    {
        if (empty($srcset)) {
            return null;
        }

        $buffer = [];

        foreach ($srcset as $size => $file) {
            $buffer[] = $file.' '.$size.'w';
        }

        return implode(', ', $buffer);
    }

    /**
     * addSrcset.
     *
     * @param string &$string              Param
     * @param object $adaptiveImagesHelper Param
     */
    public function addSrcsetSizes(&$string, $adaptiveImagesHelper = null)
    {
        if ((empty($this->srcsetDictionary)) || (empty($this->sizes))) {
            return;
        }

        if ($adaptiveImagesHelper) {
            $stringArray = $adaptiveImagesHelper->protectString($string);
        } else {
            $stringArray = [$string];
        }

        foreach ($stringArray as $i => &$substring) {
            if ($i % 2) {
                continue;
            }

            $stringArray[$i] = self::insertSrcset($substring);
        }

        $string = implode('', $stringArray);
    }

    /**
     * Get the value of srcset Dictionary.
     */
    public function getSrcsetDictionary()
    {
        return $this->srcsetDictionary;
    }

    /**
     * Set the value of srcset.
     *
     * @param mixed $srcsetDictionary
     *
     * @return self
     */
    public function setSrcsetDictionary($srcsetDictionary)
    {
        $this->srcsetDictionary = $srcsetDictionary;

        return $this;
    }

    /**
     * Get the value of sizes.
     */
    public function getSizes()
    {
        return $this->sizes;
    }

    /**
     * Set the value of sizes.
     *
     * @param mixed $sizes
     *
     * @return self
     */
    public function setSizes($sizes)
    {
        $this->sizes = $sizes;

        return $this;
    }

    /**
     * insertSrcset.
     *
     * @param string &$string Param
     */
    private function insertSrcset($string)
    {
        $output = [];

        $list = explode('<img', $string);

        // <script + 1 img
        if (count($list) < 2) {
            return $string;
        }

        foreach ($list as $i => $item) {
            // Source detection
            $src = strpos($item, 'src=');

            if (false === $src) {
                // Skipping it
                $output[] = $item;

                continue;
            }

            // Img end detection
            $end = strpos($item, '>');

            if (false === $end) {
                // Skipping it
                $output[] = $item;

                continue;
            }

            if ($src >= $end) {
                // Skipping it
                $output[] = $item;

                continue;
            }

            $parts = preg_split('/["\']/', substr($item, $src));

            if (count($parts) < 2) {
                // Skipping it
                $output[] = $item;

                continue;
            }

            $imgsrc = $parts[1];

            if (!isset($this->srcsetDictionary[$imgsrc])) {
                // Skipping it
                $output[] = $item;

                continue;
            }

            $srcset = $this->srcsetDictionary[$imgsrc];
            $srcsetAttr = $this->generateSrcset($srcset);

            if (empty($srcsetAttr)) {
                // Skipping it
                $output[] = $item;

                continue;
            }

            // We have a confirmed src
            $output[] = substr($item, 0, $src)
            .' srcset="'.$srcsetAttr.'" '
            .' sizes="'.$this->sizes.'" src'
            .substr($item, $src + 3);
        }

        $string = implode('<img', $output);

        return $string;
    }
}
