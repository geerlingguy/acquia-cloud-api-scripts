# Acquia Cloud CI/CD Scripts

These scripts could be run with something like Jenkins CI to run Acquia Cloud CI/CD tasks for all environments.

This set of scripts is used for deployments and other automation tasks, and requires the [Acquia SDK for PHP](https://github.com/acquia/acquia-sdk-php), which is installed via composer (see setup instructions below).

## Setup

  1. `cd` into this directory and run `composer install` to install required dependencies.
  2. Make sure the environment variables `ACQUIA_CLOUD_USERNAME` and `ACQUIA_CLOUD_PASSWORD` are configured (for testing, please use your own credentials—the password is the 'Cloud API key' available though the Acquia Insight UI, e.g. at `https://accounts.acquia.com/account/[your-user-id]/security`).
  3. Copy `example.config.php` to `config.php` and set your Acquia Cloud Hosting environment-specific configuration.
  4. Run one of the scripts, e.g. `php database-cleanup.php` to clean out all user-generated database backups in non-production environments.
