<?php

namespace App\Controller\Pages;

use App\Core\View;
use App\Model\Entity\Organization;

class Home extends Page {

  /**
   * Get the home page content
   * @return string
   */
  public static function getHome() {
    
    $obOrganization = new Organization;
    
    $content = View::render('pages/home', [
      'name'        => $obOrganization->getName(),
      'description' => $obOrganization->getDescription(),
      'site'        => $obOrganization->getSite()
    ]);
    return parent::getPage($content);
  }
}