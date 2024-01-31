<?php 

use App\Http\Response;
use App\Controller\Admin\Admin;

// Inclui a rota home de admin
include_once __DIR__ . '/admin/login.php';

// Rota admin
$obRouter->get('/admin', [
  'middlewares' => ['required-admin-login'],
  function () {
    return new Response(200, Admin::getHome());
  }
]);


// Rota admin
$obRouter->get('/admin', [
  'middlewares' => ['required-admin-login'],
  function () {
    return new Response(200, Admin::getHome());
  }
]);
