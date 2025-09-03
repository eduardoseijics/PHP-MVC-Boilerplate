<?php

namespace App\Session;

class SessionManager
{
  /**
   * Start session if it hasn't been started yet.
   */
  public static function start(): void
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
  }

  /**
   * Define a session value.
   * @param string $key The session key.
   * @param mixed $value The value to be stored.
   */
  public static function set(string $key, mixed $value): void
  {
    self::start();
    $_SESSION[$key] = $value;
  }

  /**
   * Get a value from the session.
   * @param string $key The session key.
   * @param mixed $default Default value if key doesn't exist.
   * @return mixed
   */
  public static function get(string $key, mixed $default = null): mixed
  {
    self::start();
    return $_SESSION[$key] ?? $default;
  }

  /**
   * Verify if a key exists in the session.
   * @param string $key The key to be checked.
   * @return bool
   */
  public static function has(string $key): bool
  {
    self::start();
    return isset($_SESSION[$key]);
  }

  /**
   * Remove a value from the session.
   * @param string $key The key to be removed.
   */
  public static function remove(string $key): void
  {
    self::start();
    unset($_SESSION[$key]);
  }

  /**
   * Destroy the session and all its data.
   * @return void
   */
  public static function destroy(): void
  {
    self::start();
    session_unset();
    session_destroy();
  }
}
