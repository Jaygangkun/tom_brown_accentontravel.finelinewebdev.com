<?php
// namespace administrator\components\com_jmap\libraries\framework\composer;
/**
 *
 * @package JMAP::administrator::components::com_jmap
 * @subpackage framework
 * @subpackage composer
 * @author Joomla! Extensions Store
 * @copyright (C) 2015 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// autoload_psr4.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__)) . '/Google';
$baseDir = dirname($vendorDir);

return array(
    'Psr\\Log\\' => array($vendorDir . '/psr/log/Psr/Log'),
    'Psr\\Http\\Message\\' => array($vendorDir . '/psr/http-message/src'),
    'Psr\\Http\\Client\\' => array($vendorDir . '/psr/http-client/src'),
    'Psr\\Cache\\' => array($vendorDir . '/psr/cache/src'),
    'Monolog\\' => array($vendorDir . '/monolog/monolog/src/Monolog'),
    'GuzzleHttp\\Psr7\\' => array($vendorDir . '/guzzlehttp/psr7/src'),
    'GuzzleHttp\\Promise\\' => array($vendorDir . '/guzzlehttp/promises/src'),
    'GuzzleHttp\\' => array($vendorDir . '/guzzlehttp/guzzle/src'),
    'Google\\Auth\\' => array($vendorDir . '/google/auth/src'),
    'Google\\' => array($vendorDir . '/google/apiclient/src'),
);
