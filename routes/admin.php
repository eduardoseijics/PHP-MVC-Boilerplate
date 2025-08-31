<?php

use App\Http\Request;
use App\Http\Response;
use App\Controller\Admin\Login;

$obRouter->get('/admin', [
  'middlewares' => [
    'required-admin-login',
  ],
  function(Request $request) {
    return new Response(Response::HTTP_OK,'Admin');
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