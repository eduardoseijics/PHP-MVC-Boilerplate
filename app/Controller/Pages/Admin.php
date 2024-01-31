<?php

namespace App\Controller\Admin;

use App\Core\View;
use App\Controller\Pages\Page;

class Admin extends Page {

  public static function getHome() {        
    $content = 'conteudo da home do painel admin';
    return self::getPage($content);
  }

  public static function getStaticPage($path, $title = 'Fatec Araçatuba - Admin') {
        
    $content = View::render($path);
    return self::getPage($content, $title);
  }
}