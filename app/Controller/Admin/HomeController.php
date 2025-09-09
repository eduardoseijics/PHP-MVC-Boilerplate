<?php

namespace App\Controller\Admin;

use App\Core\View;
use App\Http\Request;

class HomeController extends Page{
  
  /**
   * Get home page
   * @param Request $request
   * @return string
   */
  public static function getHome(Request $request): string
  {
    $content = View::render('admin/modules/home/index');

    return parent::getPanel('Home | Admin', $content, 'home');
  }
}