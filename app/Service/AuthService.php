<?php

namespace App\Service;

use App\Model\Entity\User;

class AuthService
{
  /**
   * Validate user credentials.
   * @param string $email
   * @param string $password
   * @return User|null
   */
  public function validateCredentials(string $email, string $password): ?User
  {
    // Search user by email
    $user = User::findByEmail($email);

    // Validate existence and password
    if (!$user || !password_verify($password, $user->getPassword())) {
      return null;
    }

    return $user;
  }
}
