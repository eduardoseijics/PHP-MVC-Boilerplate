<?php

namespace App\Controller\Pages;

use App\Core\View;
use App\Model\Entity\Organization;

class About extends Page {

  public static function getAbout(): string
  {

    $obOrganization = new Organization;
    
    $content = View::render('pages/about', [
      'name'        => $obOrganization->getName(),
      'description' => $obOrganization->getDescription(),
      'site'        => $obOrganization->getSite()
    ]);
    return parent::getPage($content);
  }
}