<?php
// namespace administrator\components\com_jmap\framework\google;
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

/**
 * Service definition for AnalyticsData (v1alpha).
 *
 * <p>
 * Accesses report data in Google Analytics.</p>
 *
 * <p>
 * For more information about this service, see the API
 * <a href="https://developers.google.com/analytics/trusted-testing/analytics-data/" target="_blank">Documentation</a>
 * </p>
 *
 * @author Google, Inc.
 */
class Google_Service_AnalyticsData extends Google_Service
{
  /** View and manage your Google Analytics data. */
  const ANALYTICS =
      "https://www.googleapis.com/auth/analytics";
  /** View your Google Analytics data. */
  const ANALYTICS_READONLY =
      "https://www.googleapis.com/auth/analytics.readonly";

  public $properties;
  public $v1alpha;
  
  /**
   * Constructs the internal representation of the AnalyticsData service.
   *
   * @param Google_Client $client The client used to deliver requests.
   * @param string $rootUrl The root URL used for requests to the service.
   */
  public function __construct(Google_Client $client, $rootUrl = null)
  {
    parent::__construct($client);
    $this->rootUrl = $rootUrl ?: 'https://analyticsdata.googleapis.com/';
    $this->servicePath = '';
    $this->batchPath = 'batch';
    $this->version = 'v1alpha';
    $this->serviceName = 'analyticsdata';

    $this->properties = new Google_Service_AnalyticsData_Resource_Properties(
        $this,
        $this->serviceName,
        'properties',
        array(
          'methods' => array(
            'getMetadata' => array(
              'path' => 'v1alpha/{+name}',
              'httpMethod' => 'GET',
              'parameters' => array(
                'name' => array(
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ),
              ),
            ),
          )
        )
    );
    $this->v1alpha = new Google_Service_AnalyticsData_Resource_V1alpha(
        $this,
        $this->serviceName,
        'v1alpha',
        array(
          'methods' => array(
            'batchRunPivotReports' => array(
              'path' => 'v1alpha:batchRunPivotReports',
              'httpMethod' => 'POST',
              'parameters' => array(),
            ),'batchRunReports' => array(
              'path' => 'v1alpha:batchRunReports',
              'httpMethod' => 'POST',
              'parameters' => array(),
            ),'getUniversalMetadata' => array(
              'path' => 'v1alpha/universalMetadata',
              'httpMethod' => 'GET',
              'parameters' => array(),
            ),'runPivotReport' => array(
              'path' => 'v1alpha:runPivotReport',
              'httpMethod' => 'POST',
              'parameters' => array(),
            ),'runReport' => array(
              'path' => 'v1alpha:runReport',
              'httpMethod' => 'POST',
              'parameters' => array(),
            ),
          )
        )
    );
  }
}
