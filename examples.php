<?php

/**
 * @file
 * Example usage of the Acquia Cloud SDK for PHP.
 */

require_once 'vendor/autoload.php';
require_once 'config.php';

use Acquia\Cloud\Api\CloudApiClient;

$cloudapi = CloudApiClient::factory(array(
  'username' => getenv('ACQUIA_CLOUD_USERNAME'),
  'password' => getenv('ACQUIA_CLOUD_PASSWORD'),
));

// Get all available sites.
// $sites = $cloudapi->sites();

// Get information about a site.
// $site = $cloudapi->site($site);

// Get all available environments.
// $environments = $cloudapi->environments($site);

// Get information about an environment.
// $environment = $cloudapi->environment($site, 'test');

// Get information about all databases.
// $databases = $cloudapi->databases($site);

// Get information about an environment's database.
// $database = $cloudapi->database($site, $database);

// Back up an environment's database.
// $backup = $cloudapi->createDatabaseBackup($site, 'test', $database, '12345');

// Get a list of all an environment's database backups.
$backups = $cloudapi->databaseBackups($site, 'test', $database);

print_r($backups);
