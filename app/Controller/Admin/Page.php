<?php

namespace App\Controller\Admin;

use App\Core\View;

class Page {

  private static function getHeader() {
    $vars = [
      'topbar' => View::render('admin/pages/components/topbar')
    ];
    return View::render('admin/pages/components/header', $vars);
  }

  private static function getFooter() {
    return View::render('admin/pages/components/footer');
  }

  public static function getPage($content, $title = 'PHP MVC Boilerplate') {
		return View::render('admin/base', [
			'title' => $title,
			'header' => self::getHeader(),
			'content' => $content,
			'footer' => self::getFooter()
		]);
  }

  /**
   * Get a static page
   * @param string $path
   * @param string $title
   * @return string
   */
  public static function getStaticPage($path, $title = '') {
    $content = View::render($path);
    return self::getPage($content, $title);
  }
}