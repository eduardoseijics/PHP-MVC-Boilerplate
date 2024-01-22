<?php

namespace App\Core;

class View {

  /**
   * Variáveis padrões da View
   * @var array
   */
  private static $vars; 

  public static function init($vars = []) {
    self::$vars = $vars;
  }

  /**
   * @param string
   * @return string
   */
  public static function getContentView($view) {
    $file = __DIR__.'/../../resources/view/'.$view.'.html';
    return file_exists($file) ? file_get_contents($file) : '';
  }

  /**
   * @param string $view
   * @return string
   */
  public static function render(string $view, array $vars = []) {

    $contentView = self::getContentView($view);

    // MERGE DE VARIAVEIS DA VIEW
    $vars = array_merge(self::$vars, $vars);

    $keys = array_keys($vars);
    $keys = array_map(function ($item) {
      return '{{'.$item.'}}';
    }, $keys);
    return str_replace($keys, array_values($vars), $contentView);
  }
}