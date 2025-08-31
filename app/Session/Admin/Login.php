<?php

namespace App\Session\Admin;

use App\Model\Entity\User;

class Login {

  /**
   * Performs user login
   * @param  User $user
   * @return boolean
   */
  public static function login(User $user): bool 
  {
    self::init();
    
    $_SESSION['user'] = [
      'id'    => $user->getId(),
      'name'  => $user->getName(),
      'email' => $user->getEmail(),
      'type'  => $user->getType()
    ];

    return true;
  }

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
   * Checks if the user is logged in
   * @return boolean
   */
  public static function isLogged(): bool
  {
    self::init();
    return isset($_SESSION['user']['id']);
  }

  /**
   * Performs user logout
   * @return boolean
   */
  public static function logout(): bool
  {
    self::init();

    // Remove all session variables
    session_destroy();

    return true;
  }
}