<?php

namespace App\Domain\Shared\ValueObject;

final class Email implements ValueObjectInterface
{
  private string $value;

  public function __construct(string $value)
  {
    $this->validate($value);
    $this->value = $value;
  }

  /**
   * Validates domain rules for Email
   * @return void
   * @throws \InvalidArgumentException
   */
  private function validate(string $value): void
  {
    $this->assertMaxLength($value);
    $this->assertValidFormat($value);
  }

  /**
   * Returns the string value of the Email
   * @return string
   */
  public function value(): string
  {
    return $this->value;
  }

  /**
   * Compares two Email objects for equality
   * @param self $other
   * @return bool
   */
  public function equals(ValueObjectInterface $other): bool
  {
    return $this->value === $other->value;
  }

  /**
   * Asserts that the email length is valid
   * @param string $value
   * @return void
   * @throws \InvalidArgumentException
   */
  private function assertMaxLength($value): void
  {
    if (strlen($value) > 255) {
      throw new \InvalidArgumentException("Email must not exceed 255 characters");
    }
  }

  /**
   * Asserts that the email format is valid
   * @param string $value
   * @return void
   * @throws \InvalidArgumentException
   */
  private function assertValidFormat(string $value): void
  {
    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
      throw new \InvalidArgumentException("Invalid email");
    }
  }
}
