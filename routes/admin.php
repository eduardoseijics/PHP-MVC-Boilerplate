<?php

use App\Http\Request;
use App\Http\Response;
use App\Controller\Admin\Login;
use App\Controller\Admin\AdminController;

$obRouter->get('/admin', [
  'middlewares' => [
    'required-admin-login',
  ],
  function(Request $request) {
    return new Response(Response::HTTP_OK, AdminController::getAdmin($request));
  }
]);

$obRouter->get('/admin/login', [
  function(Request $request) {
    return new Response(Response::HTTP_OK, Login::getLogin($request));
  }
]);

$obRouter->post('/admin/login', [
  'middlewares' => [
    'csrf'
  ],
  function(Request $request) {
    return new Response(Response::HTTP_OK, Login::setLogin($request));
  }
]);

$obRouter->post('/admin/logout', [
  'middlewares' => [
    'csrf'
  ],
  function(Request $request) {
    return new Response(Response::HTTP_OK, Login::setLogout($request));
  }
]);