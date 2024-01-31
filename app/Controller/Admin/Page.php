<?php

namespace App\Controller\Admin;

use App\Core\View;
use App\Controller\Admin\Menu;

class Page {

    /**
   * Renderizar o layout de paginação 
   *
   * @param  Request $request Objeto de Request
   * @param  Pagination $pagination Objeto de Pagination
   * @return string Retorna o box de paginação
   */
  public static function getPaginacao($request, $pagination)
  {
    $pages = $pagination->getPages();
    
    // Verificando a quantidade de páginas
    if (count($pages) <= 1) return '';

    $links = '';

    // URL atual (sem parâmetros GET)
    $cleanUrl = $request->getRouter()->getCurrentUrl();

    // Parâmetros GET
    $queryParams = $request->getQueryParams();

    // Renderiza os links
    foreach ($pages as $page) {
      // Altera a página
      $queryParams['page'] = $page['page'];

      // Link
      $link = $cleanUrl . '?' . http_build_query($queryParams);

      // View
      $links .= View::render('admin/pages/paginacao/link', [
        'pageNumber' => $page['page'],
        'link' => $link,
        'active' => $page['current'] ? 'active' : ''
      ]);
    }

    return View::render('admin/pages/paginacao/box', [
      'links' => $links
    ]);
  }

  private static function getHeader() {
    $vars = [
      'nome' => isset($_SESSION['user']['name']) ? 'Bem, vindo(a), '.$_SESSION['user']['name'] : ''
    ];
    return View::render('admin/components/header', $vars);
  }

  private static function getFooter() {
    return View::render('admin/components/footer');
  }

  /**
   * @param string $content
   * @param string $title
   */
  public static function getPage($content, $title = 'Livremente - Admin') {
		return View::render('admin/base', [
			'title' => $title,
      // 'menuLateral' => Menu::getMenuLateral(),
			'content' => $content,
			'header' => self::getHeader(),
			'footer' => self::getFooter()
		]);
  }

  public static function getStaticPage($path, $title = 'Livremente - Admin') {
        
    $content = View::render($path);
    return self::getPage($content, $title);
  }

}