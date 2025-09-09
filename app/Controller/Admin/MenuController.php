<?php

namespace App\Controller\Admin;

use App\Core\View;

class MenuController {

    private static $modules = [
    'home' => [
      'label' => 'Home',
      'link' => URL_ADMIN.'/home'
    ],
    'testimonials' => [
      'label' => 'Testimonials',
      'link' => URL_ADMIN.'/testimonials'
    ]
  ];

  /**
   * Get the menu
   * @param string $currentModule
   * @return string
   */
  public static function getMenu(string $currentModule): string
  {

    $links = '';

    foreach (self::$modules as $key => $value) {
      $active = ($key === $currentModule) ? 'text-danger' : '';
      $links .= View::render('admin/menu/menu-item', [
        'active' => $active,
        'link' => $value['link'],
        'label' => $value['label']
      ]);
    }
    
    return View::render('admin/menu/box', [
      'links' => $links
    ]);
  }
}