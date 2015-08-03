<?php

/**
 * @file
 * Deployment script using Acquia Cloud PHP SDK.
 *
 * The following environment variables must be set:
 *   - ACQUIA_CLOUD_USERNAME / ACQUIA_CLOUD_PASSWORD
 *   - ACQUIA_CLOUD_ENVIRONMENT (e.g. 'dev', 'test')
 *   - ACQUIA_CLOUD_TAG (e.g. branch 'dev' or tag 'tags/1.1.0')
 *
 * The following command line options can be passed:
 *   --no-copy-database
 *   --no-copy-files
 */

require_once 'vendor/autoload.php';
require_once 'config.php';

// Buffer output.
ob_start();

use Acquia\Cloud\Api\CloudApiClient;

// Build Cloud API client connection.
$cloudapi = CloudApiClient::factory(array(
  'username' => getenv('ACQUIA_CLOUD_USERNAME'),
  'password' => getenv('ACQUIA_CLOUD_PASSWORD'),
));

// Set up other required variables.
$environment = getenv('ACQUIA_CLOUD_ENVIRONMENT');
$tag = getenv('ACQUIA_CLOUD_TAG');

// Set up some booleans for whether to perform certain actions.
if (isset($argv)) {
  $copy_database = !in_array('--no-copy-database', $argv);
  $copy_files = !in_array('--no-copy-files', $argv);
}

update_console('Beginning deployment of ' . $tag . ' to ' . $environment . ' environment.');

// Create a database backup (wait for completion).
update_console('Backing up database in ' . $environment . ' environment...');
$backup = $cloudapi->createDatabaseBackup($site, $environment, $database, '12345');
if (wait_for_task_to_complete($cloudapi, $site, $backup->id())) {
  update_console('...complete!');
}

if ($copy_files) {
  // Copy files from prod to the configured environment.
  update_console('Copying files from prod to ' . $environment . '.');
  $cloudapi->copyFiles($site, 'prod', $environment);
}

if ($copy_database) {
  // Copy database from prod to the configured environment (wait for completion).
  update_console('Copying database from prod to ' . $environment . '...');
  $database = $cloudapi->copyDatabase($site, $database, 'prod', $environment);
  if (wait_for_task_to_complete($cloudapi, $site, $database->id())) {
    update_console('...complete!');
  }
}

// Deploy tag to the environment.
update_console('Deploying tag ' . $tag . ' to ' . $environment . '...');
$code = $cloudapi->pushCode($site, $environment, $tag);
if (wait_for_task_to_complete($cloudapi, $site, $code->id())) {
  update_console('...complete!');
}

// Congratulations, it's all over!
update_console('Deployment complete!');

/**
 * Pause until a given task is completed.
 *
 * @param int $id
 *   The task ID.
 *
 * @todo - This currently will loop infinitely if you pass an invalid task id.
 *   Consider fixing that ;-)
 */
function wait_for_task_to_complete($cloudapi, $site, $id = 0) {
  $task_complete = FALSE;
  while ($task_complete !== TRUE) {
    $task_status = $cloudapi->task($site, $id);
    if ($task_status->state() == 'done') {
      $task_complete = TRUE;
    }
    else {
      sleep(5);
    }
  }
  return $task_complete;
}

/**
 * Post a string to the console mid-script.
 *
 * @param string $text
 */
function update_console($text) {
  echo $text . "\n";
  ob_flush();
}

// Flush all output.
ob_end_flush();
