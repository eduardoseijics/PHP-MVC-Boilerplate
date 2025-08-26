<?php

namespace App\Utils;

use App\Core\View;
use App\Http\Request;
use App\Core\Pagination;

class PaginationRenderer
{

  /**
   * Render pagination links
   * @param Request $request
   * @param Pagination $obPagination
   * @return string
   */
  public static function render(Request $request, Pagination $obPagination): string
  {
    $pages = $obPagination->getPages();
    if (count($pages) <= 1) return '';

    $url = $request->getRouter()->getCurrentUrl();
    $queryParams = $request->getQueryParams();

    $links = '';
    foreach ($pages as $page) {
      $queryParams['page'] = $page['page'];
      $link = $url . '?' . http_build_query($queryParams);

      $links .= View::render('pages/components/pagination/link', [
        'page' => $page['page'],
        'link' => $link,
        'active' => $page['current'] ? 'active' : ''
      ]);
    }

    return View::render('pages/components/pagination/box', [
      'links' => $links
    ]);
  }
}
