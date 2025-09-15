<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Core\View;

// Middlewares
use App\Core\Database;
use App\Security\Csrf;
use DI\ContainerBuilder;
use App\Core\Environment;
use App\Http\Middlewares\Maintenance;
use App\Http\Middlewares\ValidateCsrfToken;
use \App\Http\Middlewares\RequireAdminLogin;
use \App\Http\Middlewares\RequireAdminLogout;
use \App\Http\Middlewares\Queue as MiddlewareQueue;

Environment::load(__DIR__ . '/../');

// Debugging settings
if (getenv('APP_DEBUG')) {
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
}

// DI
$builder = new ContainerBuilder();
$builder->useAutowiring(true);
$container = $builder->build();

// Database configuration
Database::config(
  getenv('DB_HOST'),
  getenv('DB_NAME'),
  getenv('DB_USER'),
  getenv('DB_PASS'),
  getenv('DB_PORT')
);

// Define constants
define('ROOT', dirname(__DIR__));
define('URL', getenv('BASE_URL'));
define('URL_ADMIN', getenv('BASE_URL') . '/admin');

// Middlewares setup
MiddlewareQueue::setMap([
  'required-admin-logout' => RequireAdminLogout::class,
  'required-admin-login'  => RequireAdminLogin::class,
  'maintenance'           => Maintenance::class,
  'csrf'                  => ValidateCsrfToken::class
]);

MiddlewareQueue::setDefaultMiddlewares([
  'maintenance'
]);

View::init([
  'URL' => URL,
  'URL_ADMIN' => URL_ADMIN,
  'ROOT' => dirname(__DIR__),
  '@csrf' => Csrf::getHiddenInput()
]);
