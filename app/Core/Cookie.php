<?php

namespace App\Core;

class Cookie {

  /**
   * Método responsável por criar cookies
   * @param  string $hash  HASH do cookie (Ex: Usuario/Email)
   * @param  string $valor Valor do cookie
   * @param  string $tempoEmSegundos Tempo em segundos
   */
  public static function set($hash,$valor,$tempoEmSegundos = null){
    $tempo = !is_null($tempoEmSegundos) ? $tempoEmSegundos : 365 * 24 * 60 * 60;
    setcookie($hash, $valor, time() + ($tempoEmSegundos), "/");
  }

  /**
   * Método responsável por retonar cookies
   * @param  string $hash  HASH do cookie (Ex: Usuario/Email)
   * @return string
   */
  public static function get($hash) {
    return isset($_COOKIE[$hash]) ? $_COOKIE[$hash] : false;
  }

  /**
   * Método responsável por matar um cookie deixando ele com tempo de expiração no passado
   * @param  string $hash
   */
  public static function unsetCookie($hash) {
    setcookie($hash, false, 1, "/");
  }
}