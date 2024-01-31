<?php

namespace App\Controller\Admin\Usuarios;

use App\Controller\Alert;
use App\Controller\Admin\Page;
use App\Model\Entity\Usuario as ModelUsuario;

class Usuarios extends Page {

  
  /**
   * Retorna mensagem de status
   *
   * @param  Request $request
   * @return string
   */
  public static function getStatus($request) {
    $queryParams = $request->getQueryParams();

    if (!isset($queryParams['status'])) return '';

    switch ($queryParams['status']) {
      case 'created':
        return Alert::getSuccess('Usuário <b>criado<b/> com sucesso!');
      case 'updated':
        return Alert::getSuccess('Usuário <b>atualizado</b> com sucesso!');
      case 'deleted':
        return Alert::getSuccess('Usuário <b>excluído</b> com sucesso!');
      case 'duplicated':
        return Alert::getError('O e-mail informado já está em uso por outro usuário!');
      default:
        return '';
    }
  }

  /**
   * Responsavel por deletar um usuario
   * @param Request $request
   * @param int $id
   * @return void
   */
  public static function deletarUsuario($request, $id) {
    $obUsuario = ModelUsuario::getUsuarioPorId($id);

    // Valida se existe uma instância de depoimento
    if (!$obUsuario instanceof ModelUsuario) {
      $request->getRouter()->redirect('/admin/usuarios');
    }

    // Exclui o depoimento
    $obUsuario->delete();

    $request->getRouter()->redirect('/admin/usuarios?status=deleted');
  }

    /**
   * Responsavel por deletar um usuario
   * @param Request $request
   * @param int $id
   * @return void
   */
  public static function editarUsuario($request, $id) {
    $obUsuario = ModelUsuario::getUsuarioPorId($id);

    // Valida se existe uma instância de depoimento
    if (!$obUsuario instanceof ModelUsuario) {
      $request->getRouter()->redirect('/admin/usuarios');
    }

    $postVars = $request->getPostVars();
    $email = $postVars['email'] ? trim($postVars['email']) : $obUsuario->getEmail();

    $userEmail = ModelUsuario::getUsuarioPorEmail($email);

    // Valida se o email já está em uso por outro usuário
    if ($userEmail instanceof ModelUsuario && $id != $userEmail->getId()) {
      $request->getRouter()->redirect('admin/usuarios/editar/'.$id.'?status=duplicated');
    }

    $nome  = isset($postVars['nome']) ? trim($postVars['nome']) : $obUsuario->getNome();
    $tipo  = isset($postVars['tipo']) ? $postVars['tipo'] : $obUsuario->getTipo();
    
    $obUsuario->setNome($nome)
              ->setEmail($email)
              ->setTipo($tipo)
              ->update();

    $request->getRouter()->redirect('/admin/usuarios?status=updated');
  }
}