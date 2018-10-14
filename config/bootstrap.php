<?php declare(strict_types=1);

namespace Davidwyly\Mb;

use Dotenv\Dotenv;

require_once(__DIR__ . '/../vendor/autoload.php');

const REQUIRED_ENV_KEYS = [
    'ENVIRONMENT',
    'APP_NAMESPACE',
    'APP_TIMEZONE',
    'TEST_OAUTH_CONSUMER_KEY',
    'TEST_OAUTH_CONSUMER_SECRET',
    'TEST_OAUTH_TOKEN_KEY',
    'TEST_OAUTH_TOKEN_SECRET',
];

$env = new Dotenv(__DIR__);
$env->load();
$env->required(constant(__NAMESPACE__ . '\REQUIRED_ENV_KEYS'));
