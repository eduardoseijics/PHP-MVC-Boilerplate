<?php

use App\Http\Request;
use App\Http\Response;
use App\Controller\Admin\HomeController;

$obRouter->get('/admin', [
  'middlewares' => ['required-admin-login'],
  'controller' => [HomeController::class, 'getHome']
]);