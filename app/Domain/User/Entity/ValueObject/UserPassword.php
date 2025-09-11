<?php

namespace App\Domain\User\Entity\ValueObject;

use App\Domain\Shared\ValueObject\ValueObjectInterface;
use App\Domain\User\Exception\InvalidUserPasswordException;

final class UserPassword implements ValueObjectInterface
{
  private string $value;

  /**
   * Constructs a new UserPassword value object
   * @param string $value
   * @throws InvalidUserPasswordException if the value is invalid
   */
  public function __construct(string $value)
  {
    $this->validate($value);
    $this->value = $value;
  }

  /**
   * Validates domain rules for UserPassword
   * @return void
   * @throws InvalidUserPasswordException
   */
  private function validate(string $value): void
  {
    $this->assertNotEmpty($value);
    $this->assertLengthBetween($value, 8, 255);
    $this->assertHasUppercase($value);
    $this->assertHasLowercase($value);
    $this->assertHasNumber($value);
    $this->assertHasSpecialCharacter($value);
    $this->assertNoLeadingOrTrailingSpaces($value);
  }

  /**
   * Returns the string value of the UserPassword
   * @return string
   */
  public function value(): string
  {
    return $this->value;
  }

  /**
   * Compares two UserPassword objects for equality
   * @param UserPassword $other
   * @return bool
   */
  public function equals(ValueObjectInterface $other): bool
  {
    return $this->value === $other->value;
  }

  /**
   * Asserts that the password is not empty
   * @param string $password
   * @return void
   * @throws InvalidUserPasswordException
   */
  private function assertNotEmpty(string $password): void
  {
    if (trim($password) === '') {
      throw new InvalidUserPasswordException('Password cannot be empty');
    }
  }

  /**
   * Asserts that the password length is between min and max
   * @param string $password
   * @param int $min
   * @param int $max
   * @return void
   * @throws InvalidUserPasswordException
   */
  private function assertLengthBetween(string $password, int $min = 8, int $max = 255): void
  {
    $len = mb_strlen($password);
    if ($len < $min) {
      throw new InvalidUserPasswordException("Password must be at least {$min} characters long");
    }
    if ($len > $max) {
      throw new InvalidUserPasswordException("Password cannot be longer than {$max} characters");
    }
  }

  /**
   * Asserts that the password contains at least one uppercase letter
   * @param string $password
   * @return void
   * @throws InvalidUserPasswordException
   */
  private function assertHasUppercase(string $password): void
  {
    if (!preg_match('/[A-Z]/', $password)) {
      throw new InvalidUserPasswordException('Password must contain at least one uppercase letter');
    }
  }

  /**
   * Asserts that the password contains at least one lowercase letter
   * @param string $password
   * @return void
   * @throws InvalidUserPasswordException
   */
  private function assertHasLowercase(string $password): void
  {
    if (!preg_match('/[a-z]/', $password)) {
      throw new InvalidUserPasswordException('Password must contain at least one lowercase letter');
    }
  }

  /**
   * Asserts that the password contains at least one number
   * @param string $password
   * @return void
   * @throws InvalidUserPasswordException
   */
  private function assertHasNumber(string $password): void
  {
    if (!preg_match('/\d/', $password)) {
      throw new InvalidUserPasswordException('Password must contain at least one number');
    }
  }

  /**
   * Asserts that the password contains at least one special character
   * @param string $password
   * @return void
   * @throws InvalidUserPasswordException
   */
  private function assertHasSpecialCharacter(string $password): void
  {
    if (!preg_match('/[\!\@\#\$\%\^\&\*\(\)_\+\-\=\[\]\{\}\|;:,.<>?]/', $password)) {
      throw new InvalidUserPasswordException('Password must contain at least one special character');
    }
  }

  /**
   * Asserts that the password does not start or end with a space
   * @param string $password
   * @return void
   * @throws InvalidUserPasswordException
   */
  private function assertNoLeadingOrTrailingSpaces(string $password): void
  {
    if (preg_match('/^\s|\s$/', $password)) {
      throw new InvalidUserPasswordException('Password cannot start or end with a space');
    }
  }
}
