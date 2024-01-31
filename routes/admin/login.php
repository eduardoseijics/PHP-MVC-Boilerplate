<?php

use App\Http\Response;
use App\Controller\Admin\Login;

$obRouter->get('/admin/login', [
  'middlewares' => ['required-admin-logout'],
  function($request) {
    return new Response(Response::HTTP_OK, Login::getPaginaLogin($request));
  }
]);

$obRouter->post('/admin/login', [
  'middlewares' => ['required-admin-logout'],
  function($request) {
    return new Response(Response::HTTP_OK, Login::setLogin($request));
  }
]);

// Rota logout
$obRouter->get('/admin/logout', [
  'middlewares' => ['required-admin-login'],
  function ($request) {
    return new Response(200, Login::setLogout($request));
  }
]);
