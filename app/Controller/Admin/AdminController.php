<?php

namespace App\Controller\Admin;

use App\Core\View;
use App\Security\Csrf;
use App\Utils\Alert;

class AdminController extends Page{

  /**
   * Get the admin dashboard page
   * @param Request $request
   * @return string
   */
  public static function getAdmin($request): string
  {
    $content = View::render('admin/pages/dashboard/dashboard', [
      'csrf' => Csrf::getHiddenInput(),
    ]);
    return parent::getPage($content, 'Dashboard | Admin');
  }
}