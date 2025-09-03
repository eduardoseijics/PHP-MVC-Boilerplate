<?php

namespace App\Session\Admin;

use App\Model\Entity\User;
use App\Session\SessionManager;

class AuthManager
{
  /**
   * Login a user and store their data in the session.
   * @param User $user
   * @return void
   */
  public function login(User $user): void
  {
    SessionManager::set('auth', [
      'user' => [
        'id'       => $user->getId(),
        'username' => $user->getName(),
        'type'     => $user->getType()
      ]
    ]);
  }

  /**
   * Logout the user.
   * @return void
   */
  public function logout(): void
  {
    SessionManager::remove('auth');
  }

  /**
   * Check if the user is logged in.
   * @return bool
   */
  public function check(): bool
  {
    $auth = SessionManager::get('auth');
    return isset($auth['user']['id']);
  }

  /**
   * Get the current logged user data.
   * @return array|null
   */
  public function user(): ?array
  {
    $auth = SessionManager::get('auth');
    return isset($auth['user']) && is_array($auth['user']) ? $auth['user'] : null;
  }


  /**
   * Check if the user has a specific role.
   * @param string $role
   * @return bool
   */
  public function hasRole(string $role): bool
  {
    $user = $this->user();
    return isset($user['type']) && $user['type'] === $role;
  }
}
