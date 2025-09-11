<?php

namespace App\Domain\Testimonial\Entity\ValueObject;

use DateTimeImmutable;

final class TestimonialDate
{
  public function __construct(private DateTimeImmutable $value) {}

  public function value(): DateTimeImmutable
  {
    return $this->value;
  }
}
