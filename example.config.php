<?php

/**
 * @file
 * Configuration settings for Acquia Cloud CI/CD Scripts.
 */

// Used for all scripts.
$site = 'prod:acquia-site-id';
$database = 'acquia-site-id';

// Used for `database-cleanup.php`.
$db_backup_cleanup_environments = array(
  'dev',
  'test',
);
