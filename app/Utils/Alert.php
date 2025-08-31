<?php

namespace App\Utils;

use App\Core\View;

class Alert
{
  const FLASH_KEY = 'flash_message';

  /**
   * Set flash message
   * @param string $message
   * @param string $type
   * @return void
   */
  public static function flash(string $message, string $type = 'success'): void
  {
    $_SESSION[self::FLASH_KEY] = [
      'message' => $message,
      'type' => $type
    ];
  }

  /**
   * Get and remove flash message
   * @return string
   */
  public static function getFlash(): string
  {
    if (!isset($_SESSION[self::FLASH_KEY])) {
      return '';
    }

    $flash = $_SESSION[self::FLASH_KEY];
    unset($_SESSION[self::FLASH_KEY]);
    
    return View::render('alert/status', [
      'alertType' => $flash['type'] === 'success' ? 'success' : 'danger',
      'message' => $flash['message']
    ]);
  }

  /**
   * Shortcut for success
   * @param string $message
   * @return void
   */
  public static function success(string $message): void
  {
    self::flash($message, 'success');
  }

  /**
   * Shortcut for error
   * @param string $message
   * @return void
   */
  public static function error(string $message): void
  {
    self::flash($message, 'danger');
  }
}
