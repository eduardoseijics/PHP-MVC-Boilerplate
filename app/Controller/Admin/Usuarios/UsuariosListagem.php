<?php

namespace App\Controller\Admin\Usuarios;

use App\Core\View;
use App\Core\Paginacao;
use App\Controller\Admin\Page;
use App\Controller\Admin\Usuarios\Usuarios;
use App\Model\Entity\Usuario as ModelUsuario;

class UsuariosListagem extends Usuarios{

  public static function getListagemUsuarios($request, &$paginacao) {
    return View::Render('admin/usuarios', [
      'itens' =>  self::getUsuariosItens($request, $paginacao)
    ]);
  }

  /**
   * Responsavel por montar o layout da lista de usuarios
   * @return string
   */
  public static function getModuloUsuarios($request) {

    $content = View::render('admin/conteudo-modulo', [
      'titulo'            => 'Usuários',
      'modulo'            => 'usuarios',
      'adicionarEntidade' => '',
      'conteudo'          => self::getListagemUsuarios($request, $paginacao),
      'paginacao'         => parent::getPaginacao($request, $paginacao),
      'status'            => parent::getStatus($request)
    ]);
    
    return Page::getPage($content);
  }

  public static function getUsuariosItens($request, &$paginacao) {
  
    // Quantidade total de registros
    $totalRows = ModelUsuario::getUsuarios(null, null, null, 'COUNT(*) as total')->fetchObject()->total;

    // Obtendo a página atual
    $queryParams = $request->getQueryParams();
    $currentPage = $queryParams['page'] ?? 1;

    // Instância de paginação
    $paginacao = new Paginacao($totalRows, $currentPage, 5);

    // Resultado da consulta da tabela de depoimentos
    $tableRows = ModelUsuario::getUsuarios(null, 'id DESC', $paginacao->getLimit());

    $itens = '';

    while ($usuario = $tableRows->fetchObject(ModelUsuario::class)) {
      $varsItem          = [];      
      $varsItem['id']    = $usuario->getId();
      $varsItem['nome']  = $usuario->getNome();
      $varsItem['email'] = $usuario->getEmail();
      $varsItem['tipo']  = $usuario->getTipo();
      $itens .= View::render('admin/components/usuarios/usuario-item', $varsItem);
    }
    
    return $itens;
  }

}