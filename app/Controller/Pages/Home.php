<?php

namespace App\Controller\Pages;

use App\Core\View;

class Home extends Page {

  public static function getHome() {
    
    $content = View::render('pages/home');
    return parent::getPage($content);
  }
}