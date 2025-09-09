<?php

use App\Http\Request;
use App\Http\Response;
use App\Controller\Admin\HomeController;

$obRouter->get('/admin', [
  'middlewares' => [
    'required-admin-login',
  ],
  function(Request $request) {
    return new Response(Response::HTTP_OK, HomeController::getHome($request));
  }
]);