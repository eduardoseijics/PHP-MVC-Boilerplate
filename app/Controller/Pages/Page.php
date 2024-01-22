<?php

namespace App\Controller\Pages;

use App\Core\View;

class Page {

  private static function getHeader() {
    $vars = [
      'topbar' => View::render('pages/components/topbar')
    ];
    return View::render('pages/components/header', $vars);
  }

  private static function getFooter() {
    return View::render('pages/components/footer');
  }

  public static function getPage($content, $title = '') {
		return View::render('pages/base', [
			'title' => $title,
			'content' => $content,
			'header' => self::getHeader(),
			'footer' => self::getFooter()
		]);
  }

  public static function getStaticPage($path, $title = '') {
        
    $content = View::render($path);
    return self::getPage($content, $title);
  }

}