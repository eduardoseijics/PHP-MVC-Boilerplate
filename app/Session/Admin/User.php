<?php

namespace App\Session\Admin;


/**
 * Gerenciar login de usuário pela sessão de admin
 */
class User {

  /**
   * Inicia a sessão
   *
   * @return void
   */
  private static function init() {
    // Verificar se a sessão está ativa
    if (session_status() != PHP_SESSION_ACTIVE) {
      session_start();
    }
  }

  public static function isUserAllowed($userType) {
    self::init();    
    return ($_SESSION['user']['tipo'] === 'admin' || $_SESSION['user']['tipo'] === $userType);
  } 

  /**
   * Verifica se o usuário logado é ádmin
   *
   * @return boolean
   */
  public static function isAdmin() {
    return $_SESSION['user']['tipo'] == 'admin';
  }
}