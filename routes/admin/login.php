<?php

use App\Http\Request;
use App\Http\Response;
use App\Controller\Admin\LoginController;

$obRouter->get('/admin/login', [
  'controller' => [LoginController::class, 'getLogin']
]);

$obRouter->post('/admin/login', [
  'middlewares' => ['csrf'],
  'controller' => [LoginController::class, 'setLogin']
]);

$obRouter->post('/admin/logout', [
  'middlewares' => ['csrf'],
  'controller' => [LoginController::class, 'setLogout']
]);

