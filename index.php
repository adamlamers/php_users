<?php
namespace PHPUsers;

require "vendor/autoload.php";
require "core/app.php";

$app = new Application();

$app->router->add('POST', '/user/create', 'UserController@create');
$app->router->add('POST', '/user/(\d+)/delete', 'UserController@remove');
$app->router->add('POST', '/user/(\d+)/update', 'UserController@update');
$app->router->add('GET', '/user/(\d+)/get', 'UserController@get');
$app->router->add('GET', '/user/(.+)/get/byEmail', 'UserController@getByEmail');

$app->run();
