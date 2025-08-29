<?php

namespace App\Core;

class View {

  /**
   * View variables
   * @var array
   */
  private static array $vars; 

  public static function init($vars = []) {
    self::$vars = $vars;
  }

  /**
   * Get view content
   * @param string
   * @return string
   */
  public static function getContentView($view) {
    $file = __DIR__.'/../../resources/view/'.$view.'.html';
    return file_exists($file) ? file_get_contents($file) : '';
  }

  /**
   * Render a template
   * @param string $view
   * @return string
   */
  public static function render(string $view, array $vars = []) {

    $contentView = self::getContentView($view);

    // DEFAULT VARS
    $vars = array_merge(self::$vars, $vars);

    $keys = array_keys($vars);
    $keys = array_map(function ($item) {
      return '{{'.$item.'}}';
    }, $keys);
    return str_replace($keys, array_values($vars), $contentView);
  }
}