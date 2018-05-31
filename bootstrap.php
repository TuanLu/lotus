<?php
// Turn on debug.
error_reporting(E_ALL);
ini_set('display_errors', 'On');
use Slim\Views\PhpRenderer;

// Include Composer autoloader.
require_once __DIR__ . '/vendor/autoload.php';

// Start the session.
session_cache_limiter(false);
session_start();

chdir(dirname(__DIR__));
// Configure the Slim app.
// https://www.slimframework.com/docs/objects/application.html
$settings = require 'config/app.php';


$container = new \Slim\Container($settings);
// view renderer
$container['view'] = function ($c) {
  $settings = $c->get('settings')['renderer'];
  return new PhpRenderer($settings['template_path']);
};

$container['HomeController'] = function ($c) {
  return new \App\Controllers\HomeController($c);
};
$container['ImportController'] = function ($c) {
  return new \App\Controllers\ImportController($c);
};
$container['ReportController'] = function ($c) {
  return new \App\Controllers\ReportController($c);
};
$container['ReportByProvinceController'] = function ($c) {
  return new \App\Controllers\ReportByProvinceController($c);
};
$container['StoreController'] = function ($c) {
  return new \App\Controllers\StoreController($c);
};
$container['OrderController'] = function ($c) {
  return new \App\Controllers\OrderController($c);
};
$container['PlanController'] = function ($c) {
  return new \App\Controllers\PlanController($c);
};

// Get an instance of Slim.
$app = new \Slim\App($container);
