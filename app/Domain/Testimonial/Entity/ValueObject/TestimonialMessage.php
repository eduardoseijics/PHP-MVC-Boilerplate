<?php

namespace App\Domain\Testimonial\Entity\ValueObject;

final class TestimonialMessage
{
  public function __construct(private string $value)
  {
    if (strlen($value) < 5) {
      throw new \InvalidArgumentException('Message is too short');
    }
  }

  public function value(): string
  {
    return $this->value;
  }
}
