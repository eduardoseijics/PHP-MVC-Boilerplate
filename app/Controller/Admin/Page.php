<?php

namespace App\Controller\Admin;

use App\Core\View;
use App\Controller\Admin\MenuController;

class Page {

  /**
   * Get the page
   * @param string $content
   * @param string $title
   * @return string
   */
  public static function getPage($content, $title = 'PHP MVC Boilerplate') {
		return View::render('admin/base', [
			'title' => $title,
			'content' => $content
		]);
  }

  /**
   * Get a panel
   * @param string $title
   * @param string $content
   * @param string $currentModule
   * @return string
   */
  public static function getPanel($title = '', $content = '', $currentModule = 'home'): string
  {
    $vars = [
      'menu' => MenuController::getMenu($currentModule),
      'content' => $content
    ];
    $panelContent = View::render('admin/panel', $vars);

    return self::getPage($panelContent, $title);
  }
}