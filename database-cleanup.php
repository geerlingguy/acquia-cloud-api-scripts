<?php

/**
 * @file
 * Delete all User-generated database backups in configured environments.
 */

require_once 'vendor/autoload.php';
require_once 'config.php';

use Acquia\Cloud\Api\CloudApiClient;

$cloudapi = CloudApiClient::factory(array(
  'username' => getenv('ACQUIA_CLOUD_USERNAME'),
  'password' => getenv('ACQUIA_CLOUD_PASSWORD'),
));

foreach ($database_cleanup_environments as $environment) {
  // Get a list of all an environment's database backups.
  $backups = $cloudapi->databaseBackups($site, $environment, $database);

  $count = 0;
  foreach ($backups as $backup) {
    if ($backup->type() == 'ondemand') {
      $backup_id = $backup->id();
      $cloudapi->deleteDatabaseBackup($site, $environment, $database, $backup->id());
      $count++;
    }
  }

  printf("Deleted %d ondemand database backups in %s environment.\n", $count, $environment);
}
