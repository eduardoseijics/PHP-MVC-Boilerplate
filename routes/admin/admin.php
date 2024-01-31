<?php 

use App\Http\Response;
use App\Controller\Admin\Admin;
use App\Controller\Admin\Usuarios\Usuarios;
use App\Controller\Admin\Usuarios\UsuariosListagem;
use App\Controller\Admin\Usuarios\UsuariosFormulario;

// Inclui a rota home de admin
include_once __DIR__ . '/login.php';

// Rota admin
$obRouter->get('/admin', [
  'middlewares' => ['required-admin-login'],
  function () {
    return new Response(200, Admin::getHome());
  }
]);

// // Rota admin
// $obRouter->get('/admin/usuarios', [
//   'middlewares' => ['required-admin-login'],
//   function ($request) {
//     return new Response(200, UsuariosListagem::getModuloUsuarios($request));
//   }
// ]);

$obRouter->get('/admin/usuarios/excluir/{id}', [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request, $id) {
    return new Response(200, Usuarios::deletarUsuario($request, $id));
  }
]);

// $obRouter->get('/admin/usuarios/editar/{id}', [
//   'middlewares' => [
//     'required-admin-login'
//   ],
//   function ($request, $id) {
//     return new Response(200, UsuariosFormulario::getFormUsuario($request, $id));
//   }
// ]);

// $obRouter->post('/admin/usuarios/editar/{id}', [
//   'middlewares' => [
//     'required-admin-login'
//   ],
//   function ($request, $id) {
//     return new Response(200, Usuarios::editarUsuario($request, $id));
//   }
// ]);

