<?php

namespace App\Controller\Admin\Usuarios;

use App\Core\View;
use App\Controller\Admin\Page;
use App\Model\Entity\Usuario as ModelUsuario;

class UsuariosFormulario extends Usuarios {

  public static function getFormUsuario($request, $id) {

    $obUsuario = ModelUsuario::getUsuarioPorId($id);

    // Valida se existe uma instância de depoimento
    if (!$obUsuario instanceof ModelUsuario) {
      $request->getRouter()->redirect('/admin/usuarios');
    }

    $varsLayout = [
      'nome'        => $obUsuario->getNome(),
      'email'       => $obUsuario->getEmail(),
      'senha'       => $obUsuario->getSenha(),
      'tipo'        => $obUsuario->getTipo(),
      'selectTipo'  => self::getSelectTipo(),
      'status'      => parent::getStatus($request)
    ];
    $content = View::render('admin/components/usuarios/usuario-formulario', $varsLayout);
    return Page::getPage($content);
  }

  public static function getSelectTipo() {
    $tiposUsuario = [
      'admin'       => 'Administrador',
      'coordenador' => 'Coordenador',
      'professor'   => 'Professor',
      'aluno'       => 'Aluno',
      'colaborador' => 'Colaborador'
    ];

    return self::getSelect($tiposUsuario, 'tipo');
  }

  public static function getSelect(array $dados = [],  $name = '', $indexAtual = '', $id = '') {
    $options = '';
    foreach ($dados as $key => $dado) {
      $options .= View::render('admin/components/usuarios/usuario-formulario-option', [
        'selected' => ($key == $indexAtual) ? 'selected' : '',
        'value' => $key,
        'label' => $dado
      ]);
    }

    return View::render('admin/components/usuarios/usuario-formulario-select', [
      'options' => $options,
      'id' => $id,
      'name' => $name
    ]);
  }
}