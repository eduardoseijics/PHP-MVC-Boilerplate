<?php

namespace App\Domain\Testimonial\Entity\ValueObject;

final class TestimonialName
{
  public function __construct(private string $value)
  {
    if (trim($value) === '') {
      throw new \InvalidArgumentException('Name cannot be empty');
    }
  }

  public function value(): string
  {
    return $this->value;
  }
}
