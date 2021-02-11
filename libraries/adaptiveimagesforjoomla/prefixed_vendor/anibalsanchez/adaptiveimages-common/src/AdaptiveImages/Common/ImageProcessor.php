<?php /* This file has been prefixed by <PHP-Prefixer> for "XT Search for Algolia" on 2019-08-30 11:27:26 */

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

/*
 * ===================================================
 *  Inspired on Adaptive Images by Matt Wilcox http://adaptive-images.com
 * ===================================================
 * Homepage:  http://adaptive-images.com
 * GitHub:    https://github.com/MattWilcox/Adaptive-Images
 * Twitter:   @responsiveimg
 * ===================================================
 */

/**
 * AdaptiveImagesForJoomlaHelper class - Plugin that replaces media urls with Adaptive Images urls.
 *
 * @since       1.0
 */
class ImageProcessor
{
    private $documentRoot;

    private $aiCachePath;

    private $aiResolutions;

    private $aiJpgQuality;

    private $aiSharpen;

    private $generateSrcset = false;

    private $srcSet = [];

    private $cacheResolution = true;

    private $resolution;

    /**
     * ImageProcessor.
     *
     * @param string $documentRoot   Param
     * @param string $path           Param
     * @param string $resolutions    Param
     * @param string $generateSrcset Param
     * @param mixed  $jpgQuality
     * @param mixed  $sharpen
     */
    public function __construct($documentRoot, $path, $resolutions, $jpgQuality, $sharpen)
    {
        $this->documentRoot = $documentRoot;
        $this->aiCachePath = $path;
        $this->aiResolutions = $resolutions;
        $this->aiJpgQuality = $jpgQuality;
        $this->aiSharpen = $sharpen;

        // Does the $cachePath directory exist already?
        $cachePath = $this->documentRoot.'/'.$this->aiCachePath;

        if (!is_dir($cachePath)) {
            // No
            if (!mkdir($cachePath, 0755, true)) {
                // So make it
                if (!is_dir($cachePath)) {
                    // Check again to protect against race conditions
                    // Uh-oh, failed to make that directory
                    $this->sendErrorImage('Failed to create cache directory at: '.$cachePath);
                }
            }
        }
    }

    /**
     * Get the value of generateSrcset.
     */
    public function getGenerateSrcset()
    {
        return $this->generateSrcset;
    }

    /**
     * Set the value of generateSrcset.
     *
     * @param mixed $generateSrcset
     *
     * @return self
     */
    public function setGenerateSrcset($generateSrcset)
    {
        $this->generateSrcset = $generateSrcset;

        return $this;
    }

    /**
     * Get the value of srcSet.
     */
    public function getSrcSet()
    {
        $resolutions = $this->srcSet;
        ksort($resolutions);

        return $resolutions;
    }

    /**
     * Set the value of srcSet.
     *
     * @param mixed $srcSet
     *
     * @return self
     */
    public function setSrcSet($srcSet)
    {
        $this->srcSet = $srcSet;

        return $this;
    }

    /**
     * Get the value of cacheResolution.
     */
    public function getCacheResolution()
    {
        return $this->cacheResolution;
    }

    /**
     * Set the value of cacheResolution.
     *
     * @param mixed $cacheResolution
     *
     * @return self
     */
    public function setCacheResolution($cacheResolution)
    {
        $this->cacheResolution = $cacheResolution;

        return $this;
    }

    /**
     * getAdaptedImage.
     *
     * @param string $file Params
     *
     * @return string
     */
    public function getAdaptedImage($file)
    {
        $this->srcSet = [];
        $sourceFile = $this->documentRoot.'/'.$file;

        // Check if the file exists at all
        if (!file_exists($sourceFile)) {
            return false;
        }

        // Check that PHP has the GD library available to use for image re-sizing
        if (!extension_loaded('gd')) {
            // And we can't load it either - No GD available
            throw new \Exception('You must enable the GD extension to make use of XT Adaptive Images');
        }

        // The resolution break-points to use (screen widths, in pixels)
        $resolutions = explode(',', $this->aiResolutions);

        $requestedUri = '/'.$file;
        $requestedFile = basename($requestedUri);

        // If the requested URL starts with a slash, remove the slash
        if ('/' === substr($requestedUri, 0, 1)) {
            $requestedUri = substr($requestedUri, 1);
        }

        $resolutionTargets = [];
        $clientResolution = $this->getResolution($resolutions);

        $sendImage = $this->generateImage($sourceFile, $requestedUri, $clientResolution);

        if (!$this->generateSrcset) {
            // Nothing else to do
            return $sendImage;
        }

        foreach ($resolutions as $resolution) {
            $file = $this->generateImage($sourceFile, $requestedUri, $resolution);

            // We have a new file
            if ($file !== $sourceFile) {
                $relativeFile = str_replace($this->documentRoot.'/', '', $file);
                $this->srcSet[$resolution] = $relativeFile;
            }
        }

        if (!empty($this->srcSet)) {
            $dimensions = getimagesize($sourceFile);
            $width = $dimensions[0];
            $relativeFile = str_replace($this->documentRoot.'/', '', $sourceFile);
            $this->srcSet[$width] = $relativeFile;
        }

        return $sendImage;
    }

    /**
     * generateImage - generates the given cache file for the given source file with the given resolution.
     *
     * @param string $sourceFile   Params
     * @param int    $requestedUri Params
     * @param int    $resolution   Params
     *
     * @return string
     */
    public function generateImage($sourceFile, $requestedUri, $resolution)
    {
        $cacheFile = $this->documentRoot.'/'.$this->aiCachePath.'/'.$resolution.'/'.$requestedUri;

        // Use the resolution value as a path variable and check to see if an image of the same name exists at that path
        if (file_exists($cacheFile)) {
            // If cache watching is enabled, compare cache and source modified dates to ensure the cache isn't stale
            if ($this->isCacheFresh($sourceFile, $cacheFile)) {
                return $cacheFile;
            }
        }

        $extension = strtolower(pathinfo($sourceFile, PATHINFO_EXTENSION));

        // Check the image dimensions
        $dimensions = getimagesize($sourceFile);
        $width = $dimensions[0];
        $height = $dimensions[1];

        // Do we need to downscale the image?
        if ($width <= $resolution) {
            // No, because the width of the source image is already less than the client width
            return $sourceFile;
        }

        // We need to resize the source image to the width of the resolution breakpoint we're working with
        $ratio = $height / $width;
        $newWidth = $resolution;
        $newHeight = ceil($newWidth * $ratio);

        // Re-sized image
        $dst = imagecreatetruecolor($newWidth, $newHeight);

        switch ($extension) {
            case 'png':
                // Original image
                $src = @imagecreatefrompng($sourceFile);

                break;
            case 'gif':
                // Original image
                $src = @imagecreatefromgif($sourceFile);

                break;
            default:
                // Original image
                $src = @imagecreatefromjpeg($sourceFile);

                // Enable interlancing (progressive JPG, smaller size file)
                imageinterlace($dst, true);

                break;
        }

        if ('png' === $extension) {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
            imagefilledrectangle($dst, 0, 0, $newWidth, $newHeight, $transparent);
        }

        // Do the resize in memory
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($src);

        // Sharpen the image?
        // NOTE: requires PHP compiled with the bundled version of GD (see http://php.net/manual/en/function.imageconvolution.php)
        if (($this->aiSharpen) && (function_exists('imageconvolution'))) {
            $intSharpness = $this->findSharp($width, $newWidth);
            $arrMatrix = [
                [
                    -1,
                    -2,
                    -1,
                ],
                [
                    -2,
                    $intSharpness + 12,
                    -2,
                ],
                [
                    -1,
                    -2,
                    -1,
                ],
            ];
            imageconvolution($dst, $arrMatrix, $intSharpness, 0);
        }

        $cacheDir = dirname($cacheFile);

        // Does the directory exist already?
        if (!is_dir($cacheDir)) {
            if (!mkdir($cacheDir, 0755, true)) {
                // Check again if it really doesn't exist to protect against race conditions
                if (!is_dir($cacheDir)) {
                    // Uh-oh, failed to make that directory
                    imagedestroy($dst);

                    throw new \Exception("Failed to create cache directory: ${cacheDir}");
                }
            }
        }

        if (!is_writable($cacheDir)) {
            $this->sendErrorImage("The cache directory is not writable: ${cacheDir}");
        }

        // Save the new file in the appropriate path, and send a version to the browser
        switch ($extension) {
            case 'png':
                $gotSaved = imagepng($dst, $cacheFile);

                break;
            case 'gif':
                $gotSaved = imagegif($dst, $cacheFile);

                break;
            default:
                $gotSaved = imagejpeg($dst, $cacheFile, $this->aiJpgQuality);

                break;
        }

        imagedestroy($dst);

        if (!$gotSaved && !file_exists($cacheFile)) {
            $this->sendErrorImage("Failed to create image: ${cacheFile}");
        }

        return $cacheFile;
    }

    private function getResolution($resolutions)
    {
        if (($this->cacheResolution) && ($this->resolution)) {
            return $this->resolution;
        }

        $resolution = $this->getCookieResolution($resolutions);

        // No resolution was found (no cookie or invalid cookie)
        if (!$resolution) {
            // Does the UA string indicate this is a mobile?
            // We send the lowest resolution for mobile-first approach, and highest otherwise
            $resolution = $this->isMobile() ? min($resolutions) : max($resolutions);
        }

        $this->resolution = $resolution;

        return $resolution;
    }

    private function getCookieResolution($resolutions)
    {
        // Check to see if a valid cookie exists
        if (!isset($_COOKIE['resolution'])) {
            return null;
        }

        $cookieValue = $_COOKIE['resolution'];

        // Does the cookie look valid? [whole number, comma, potential floating number]
        if (!preg_match('/^[0-9]+[,]*[0-9\\.]+$/', "${cookieValue}")) {
            // No it doesn't look valid
            setcookie('resolution', "${cookieValue}", time() - 100); // delete the mangled cookie

            return null;
        }

        // The cookie is valid, do stuff with it
        $cookieData = explode(',', $_COOKIE['resolution']);

        // The base resolution (CSS pixels)
        $clientWidth = (int) $cookieData[0];
        $totalWidth = $clientWidth;

        // Set a default, used for non-retina style JS snippet
        $pixelDensity = 1;

        if (@$cookieData[1]) {
            // The device's pixel density factor (physical pixels per CSS pixel)
            $pixelDensity = $cookieData[1];
        }

        // Make sure the supplied break-points are in reverse size order
        rsort($resolutions);

        // By default use the largest supported break-point
        $resolution = $resolutions[0];

        // If pixel density is not 1, then we need to be smart about adapting and fitting into the defined breakpoints
        if (1 !== $pixelDensity) {
            // Required physical pixel width of the image
            $totalWidth = $clientWidth * $pixelDensity;

            // The required image width is bigger than any existing value in $resolutions
            if ($totalWidth > $resolutions[0]) {
                // Firstly, fit the CSS size into a break point ignoring the multiplier
                foreach ($resolutions as $breakPoint) {
                    // Filter down
                    if ($totalWidth <= $breakPoint) {
                        $resolution = $breakPoint;
                    }
                }

                // Now apply the multiplier
                $resolution = $resolution * $pixelDensity;
            }

            // The required image fits into the existing breakpoints in $resolutions
            else {
                foreach ($resolutions as $breakPoint) {
                    // Filter down
                    if ($totalWidth > $breakPoint) {
                        break;
                    }

                    $resolution = $breakPoint;
                }
            }
        } else {
            // Pixel density is 1, just fit it into one of the breakpoints
            foreach ($resolutions as $breakPoint) {
                // Filter down
                if ($totalWidth > $breakPoint) {
                    break;
                }

                $resolution = $breakPoint;
            }
        }

        return $resolution;
    }

    /**
     * isMobile - Mobile detection NOTE: only used if the cookie isn't available.
     *
     * @return bool
     */
    private function isMobile()
    {
        if (array_key_exists('HTTP_USER_AGENT', $_SERVER)) {
            $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);

            return false !== strpos($userAgent, 'mobile');
        }

        return false;
    }

    /**
     * isCacheFresh - refreshes the cached image if it's outdated.
     *
     * @param string $sourceFile Params
     * @param int    $cacheFile  Params
     *
     * @return int
     */
    private function isCacheFresh($sourceFile, $cacheFile)
    {
        if (file_exists($cacheFile)) {
            // Not modified
            if (filemtime($cacheFile) >= filemtime($sourceFile)) {
                return $cacheFile;
            }

            // Modified, clear it
            unlink($cacheFile);

            return false;
        }

        return true;
    }

    /**
     * findSharp - sharpen images function.
     *
     * @param int $intOrig  Params
     * @param int $intFinal Params
     *
     * @return int
     */
    private function findSharp($intOrig, $intFinal)
    {
        $intFinal = $intFinal * (750.0 / $intOrig);
        $intA = 52;
        $intB = -0.27810650887573124;
        $intC = .00047337278106508946;
        $intRes = $intA + $intB * $intFinal + $intC * $intFinal * $intFinal;

        return max(round($intRes), 0);
    }
}
