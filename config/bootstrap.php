<?php declare(strict_types=1);

namespace Example\App;

use Dotenv\Dotenv;

require_once(__DIR__ . '/../vendor/autoload.php');

const REQUIRED_ENV_KEYS = [
    'ENVIRONMENT',
    'APP_NAMESPACE',
    'APP_TIMEZONE',
    'DATABASE_READ_HOST',
    'DATABASE_READ_NAME',
    'DATABASE_READ_USERNAME',
    'DATABASE_READ_PASSWORD',
    'DATABASE_READ_CHARSET',
    'DATABASE_WRITE_HOST',
    'DATABASE_WRITE_NAME',
    'DATABASE_WRITE_USERNAME',
    'DATABASE_WRITE_PASSWORD',
    'DATABASE_WRITE_CHARSET',
    'TEST_OAUTH_CONSUMER_KEY',
    'TEST_OAUTH_CONSUMER_SECRET',
    'TEST_OAUTH_TOKEN_KEY',
    'TEST_OAUTH_TOKEN_SECRET',
];

$env = new Dotenv(__DIR__);
$env->load();
$env->required(constant(__NAMESPACE__ . '\REQUIRED_ENV_KEYS'));
