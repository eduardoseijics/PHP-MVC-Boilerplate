<?php

namespace App\Session\Admin;
class User {

  /**
   * Starts the session
   * @return void
   */
  private static function init(): void
  {
    // Check if the session is not already started
    if (session_status() != PHP_SESSION_ACTIVE) {
      session_start();
    }
  }

  /**
   * Checks if the user is allowed
   * @param string $userType
   * @return boolean
   */
  public static function isUserAllowed(string $userType): bool 
  {
    self::init();    
    return ($_SESSION['user']['type'] === 'admin' || $_SESSION['user']['type'] === $userType);
  } 

  /**
   * Checks if the user is an admin
   * @return boolean
   */
  public static function isAdmin(): bool
  {
    return $_SESSION['user']['type'] == 'admin';
  }
}