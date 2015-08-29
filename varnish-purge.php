<?php

/**
 * @file
 * Varnish purge script using Acquia Cloud PHP SDK.
 *
 * The following environment variables must be set:
 *   - ACQUIA_CLOUD_USERNAME / ACQUIA_CLOUD_PASSWORD
 *   - ACQUIA_CLOUD_SITE (e.g. 'prod:acquia-site-id')
 *   - ACQUIA_CLOUD_ENVIRONMENT (e.g. 'dev', 'test')
 */

require_once 'vendor/autoload.php';

use Acquia\Cloud\Api\CloudApiClient;

// Build Cloud API client connection.
$cloudapi = CloudApiClient::factory(array(
  'username' => getenv('ACQUIA_CLOUD_USERNAME'),
  'password' => getenv('ACQUIA_CLOUD_PASSWORD'),
));

// Set up other required variables.
$site = getenv('ACQUIA_CLOUD_SITE');
$environment = getenv('ACQUIA_CLOUD_ENVIRONMENT');

// Get a list of all an environment's domains.
$domains = $cloudapi->domains($site, $environment);
foreach ($domains as $domain) {
  $cloudapi->purgeVarnishCache($site, $environment, $domain);
  printf("Purged Varnish cache for %s in %s environment.\n", $domain, $environment);
}
