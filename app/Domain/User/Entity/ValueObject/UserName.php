<?php

namespace App\Domain\User\Entity\ValueObject;

use App\Domain\User\Exception\InvalidUserNameException;

final class UserName
{
  private string $value;

  /**
   * Constructs a new UserName value object
   * @param string $value
   * @throws InvalidUserNameException if the value is invalid
   */
  public function __construct(string $value)
  {
    $this->validate($value);
    $this->value = $value;
  }

  /**
   * Validates domain rules for UserName
   * @return void
   * @throws InvalidUserNameException
   */
  private function validate(string $value): void
  {
    $this->assertNotEmpty($value);
    $this->assertLengthBetween($value, 2, 255);
    $this->assertValidCharacters($value);
    $this->assertNoConsecutiveSpaces($value);
    $this->assertNoLeadingOrTrailingSpaces($value);
  }

  /**
   * Returns the string value of the UserName
   * @return string
   */
  public function value(): string
  {
    return $this->value;
  }

  /**
   * Compares two UserName objects for equality
   * @param UserName $other
   * @return bool
   */
  public function equals(self $other): bool
  {
    return $this->value === $other->value;
  }

  /**
   * Asserts that the value is not empty
   * @param string $value
   * @return void
   * @throws InvalidUserNameException
   */
  private function assertNotEmpty(string $value): void
  {
    if (trim($value) === '') {
      throw new InvalidUserNameException('Name cannot be empty');
    }
  }

  /**
   * Asserts that the value length is between min and max
   * @param string $value
   * @param int $min
   * @param int $max
   * @return void
   * @throws InvalidUserNameException
   */
  private function assertLengthBetween(string $value, int $min, int $max): void
  {
    $len = mb_strlen($value);
    if ($len < $min) {
      throw new InvalidUserNameException("Name must be at least {$min} characters long");
    }
    if ($len > $max) {
      throw new InvalidUserNameException("Name cannot be longer than {$max} characters");
    }
  }

  /**
   * Asserts that the value contains only letters, spaces, apostrophes and hyphens
   * @param string $value
   * @return void
   * @throws InvalidUserNameException
   */
  private function assertValidCharacters(string $value): void
  {
    if (!preg_match('/^[\p{L}\p{M}\'\-\s]+$/u', $value)) {
      throw new InvalidUserNameException('Name can only contain letters, spaces, apostrophes and hyphens');
    }
  }

  /**
   * Asserts that the value does not contain consecutive spaces
   * @param string $value
   * @return void
   * @throws InvalidUserNameException
   */
  private function assertNoConsecutiveSpaces(string $value): void
  {
    if (preg_match('/\s{2,}/', $value)) {
      throw new InvalidUserNameException('Name cannot contain consecutive spaces');
    }
  }

  /**
   * Asserts that the value does not start or end with a space
   * @param string $value
   * @return void
   * @throws InvalidUserNameException
   */
  private function assertNoLeadingOrTrailingSpaces(string $value): void
  {
    if (preg_match('/^\s|\s$/', $value)) {
      throw new InvalidUserNameException('Name cannot start or end with a space');
    }
  }
}
