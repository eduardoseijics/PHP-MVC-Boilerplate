<?php

namespace App\Http;

use App\Controller\Pages\Page;

class PageNotFound {

  /**
   * Get the 404 page
   * @param string $package
   * @return string
   */
  public static function get404($package = 'site') {

    if($package === 'admin') {
      return 'Page not found - Admin';
    }

    return Page::getStaticPage('pages/404');
  }
}