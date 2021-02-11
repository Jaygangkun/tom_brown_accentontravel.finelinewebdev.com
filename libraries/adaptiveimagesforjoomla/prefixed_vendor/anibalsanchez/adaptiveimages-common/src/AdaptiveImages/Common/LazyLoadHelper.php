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
 * LazyLoadHelper.
 *
 * @since       1.0
 */
class LazyLoadHelper
{
    const J_QUERY_LAZY_LOAD = 1;
    const VANILLA_LAZY_LOAD = 2;

    private $dataOriginal = 'data-original';

    private $dataSrc = 'data-src';

    private $libMode = self::J_QUERY_LAZY_LOAD;

    private $adaptiveImagesHelper;

    public function __construct($adaptiveImagesHelper = null)
    {
        $this->adaptiveImagesHelper = $adaptiveImagesHelper;
    }

    /**
     * replaceLazyLoad.
     *
     * @param string &$string              Param
     * @param array  $classes              Param
     * @param object $adaptiveImagesHelper Param
     */
    public function replaceLazyLoad(&$string, $classes)
    {
        if (is_array($string)) {
            $this->replaceLoadInList($string, $classes);

            return;
        }

        if ($this->adaptiveImagesHelper) {
            $stringArray = $this->adaptiveImagesHelper->protectString($string);
        } else {
            $stringArray = [$string];
        }

        foreach ($stringArray as $i => &$substring) {
            if ($i % 2) {
                continue;
            }

            $stringArray[$i] = $this->replaceLoadDataOriginalList($substring, $classes);
        }

        $string = implode('', $stringArray);
    }

    /**
     * Get the value of libMode.
     */
    public function getLibMode()
    {
        return $this->libMode;
    }

    /**
     * Set the value of libMode.
     *
     * @param mixed $libMode
     *
     * @return self
     */
    public function setLibMode($libMode)
    {
        $this->libMode = $libMode;

        return $this;
    }

    /**
     * replaceLoadInList.
     *
     * @param string &$array  Param
     * @param array  $classes Param
     */
    private function replaceLoadInList(&$array, $classes)
    {
        foreach ($array as &$val) {
            $this->replaceLoadDataOriginalList($val, $classes);
        }
    }

    /**
     * replaceLoadDataOriginalList.
     *
     * @param string &$string Param
     * @param array  $classes Param
     *
     * @return $string
     */
    private function replaceLoadDataOriginalList(&$string, $classes)
    {
        foreach ($classes as $class) {
            $this->replaceLoadDataOriginal($string, $class);
        }

        return $string;
    }

    /**
     * replaceLoadDataOriginal.
     *
     * @param string &$string        Param
     * @param array  $detectionClass Param
     */
    private function replaceLoadDataOriginal(&$string, $detectionClass)
    {
        $dataTag = self::J_QUERY_LAZY_LOAD === $this->libMode ? $this->dataOriginal : $this->dataSrc;
        $output = [];

        $list = explode('<img', $string);

        // <script + 1 img
        if (count($list) < 2) {
            return;
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

            // DetectionClass
            $detectionClassPos = strpos($item, $detectionClass);

            if ((false === $detectionClassPos) || ($detectionClassPos >= $end)) {
                // Skipping it
                $output[] = $item;

                continue;
            }

            if ($this->adaptiveImagesHelper) {
                $parts = preg_split('/["\']/', substr($item, $src));

                if (count($parts) < 2) {
                    // Skipping it
                    $output[] = $item;

                    continue;
                }

                $imgsrc = $parts[1];

                if (($this->adaptiveImagesHelper->filetypeIsIgnored($imgsrc))
                    || ($this->adaptiveImagesHelper->fileIsIgnored($imgsrc))
                    || ($this->adaptiveImagesHelper->imageFileIsIgnored($imgsrc))) {
                    // Skipping it
                    $output[] = $item;

                    continue;
                }
            }

            // We have a confirmed src
            $newTag = substr($item, 0, $src).$dataTag.substr($item, $src + 3);

            if (self::VANILLA_LAZY_LOAD === $this->libMode) {
                $newTag = str_replace(' srcset=', ' data-srcset=', $newTag);
            }

            $output[] = $newTag;
        }

        $string = implode('<img', $output);
    }
}
