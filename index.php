<?php
namespace PHPUsers;

require "vendor/autoload.php";
require "core/app.php";

$app = new Application();

$app->router->add('POST', '/user$', 'UserController@create');
$app->router->add('DELETE', '/user/(\d+)', 'UserController@remove');
$app->router->add('POST', '/user/(\d+)', 'UserController@update');
$app->router->add('GET', '/user/(\d+)', 'UserController@get');
$app->router->add('GET', '/user/(.+)', 'UserController@getByEmail');

$app->run();
