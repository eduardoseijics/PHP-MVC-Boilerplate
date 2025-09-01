<?php

namespace App\Core;

use App\Model\Entity\User;
use App\Session\SessionManager;

class AuthManager
{
  private SessionManager $session;

  public function __construct()
  {
    $this->session = SessionManager::getInstance();
  }

  /**
   * Login a user and store their data in the session.
   * @param User $user
   * @return void
   */
  public function login(User $user): void
  {
    if(!$user instanceof User) {
      throw new \InvalidArgumentException('Invalid user');
    }
    
    $this->session->set('auth', [
      'id'       => $user->getId(),
      'username' => $user->getName(),
      'type'     => $user->getType()
    ]);
  }

  /**
   * Logout the user.
   * @return void
   */
  public function logout(): void
  {
    $this->session->remove('auth');
  }

  /**
   * Check if the user is logged in.
   * @return bool
   */
  public function check(): bool
  {
    return $this->session->has('auth');
  }

  /**
   * Get the current logged user data.
   * @return array|null
   */
  public function user(): ?array
  {
    return $this->session->get('auth');
  }

  /**
   * Check if the user has a specific role.
   * @param string $role
   * @return bool
   */
  public function hasRole(string $role): bool
  {
    $user = $this->user();
    return $user && $user['type'] === $role;
  }
}
