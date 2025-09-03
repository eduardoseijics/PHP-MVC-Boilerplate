<?php

namespace App\Utils;

use App\Core\View;
use App\Session\SessionManager;

class Alert
{
  private const SESSION_KEY = 'flash_alert';

  /**
   * Define a flash message.
   * 
   */
  public static function set(string $type, string $message): void
  {
    SessionManager::set(self::SESSION_KEY, [
      'type' => $type,
      'message' => $message
    ]);
  }

  /**
   * Return message in html and cleans session
   */
  public static function getAlert(): string
  {
    if (!SessionManager::has(self::SESSION_KEY)) {
      return '';
    }

    $flash = SessionManager::get(self::SESSION_KEY);

    // Remove immediately so it doesn't show up again
    SessionManager::remove(self::SESSION_KEY);

    return View::render('alert/alert', [
      'type' => $flash['type'],
      'message' => $flash['message']
    ]);
  }


  /**
   * Define a success message.
   * @param string $message
   * @return void
   */
  public static function success(string $message): void
  {
    self::set('success', $message);
  }

  /**
   * Define a error message.
   * @param string $message
   * @return void
   */
  public static function error(string $message): void
  {
    self::set('danger', $message);
  }

  /**
   * Define a warning message.
   * @param string $message
   * @return void
   */
  public static function warning(string $message): void
  {
    self::set('warning', $message);
  }

  /**
   * Define a info message.
   * @param string $message
   * @return void
   */
  public static function info(string $message): void
  {
    self::set('info', $message);
  }
}
