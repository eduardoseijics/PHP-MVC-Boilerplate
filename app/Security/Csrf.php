<?php

namespace App\Security;

class Csrf
{
  /**
   * Session key where CSRF token is stored
   */
  private const SESSION_KEY = '_csrf';

  /**
   * Form field name for CSRF token
   */
  private const FIELD_NAME = '_csrf';

  /**
   * Generate a new CSRF token or return existing one
   * @return string
   */
  public static function generateToken(): string
  {
    if (session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
    }

    // Only generate a new token if it doesn't exist
    if (!isset($_SESSION[self::SESSION_KEY])) {
      $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
    }

    return $_SESSION[self::SESSION_KEY];
  }

  /**
   * Return a hidden input field for forms
   * @return string
   */
  public static function getHiddenInput(): string
  {
    $token = self::generateToken();
    return '<input type="hidden" name="' . self::FIELD_NAME . '" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
  }

  /**
   * Return the name of the CSRF field
   * @return string
   */
  public static function getFieldName(): string
  {
    return self::FIELD_NAME;
  }

  /**
   * Validate the token from the request
   * @param string|null $token
   * @return bool
   */
  public static function validateToken(?string $token): bool
  {
    if (session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
    }

    // If no token in session or request, invalid
    if (!isset($_SESSION[self::SESSION_KEY]) || empty($token)) {
      return false;
    }

    // Timing attack safe comparison
    return hash_equals($_SESSION[self::SESSION_KEY], (string) $token);
  }

  /**
   * Optional: Invalidate token after successful validation (if you want one-time tokens)
   */
  public static function invalidateToken(): void
  {
    if (session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
    }

    unset($_SESSION[self::SESSION_KEY]);
  }
}
