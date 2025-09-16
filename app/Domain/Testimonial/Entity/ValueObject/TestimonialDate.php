<?php

namespace App\Domain\Testimonial\Entity\ValueObject;

use DateTimeImmutable;

final class TestimonialDate
{
  private DateTimeImmutable $value;

  public function __construct(DateTimeImmutable $value)
  {
    $this->value = $value;
  }

  // Retorna o DateTimeImmutable real
  public function value(): DateTimeImmutable
  {
    return $this->value;
  }

  // Para exibição
  public function display(): string
  {
    return $this->value->format('d/m/Y H:i:s');
  }

  // Para banco (ou conversão automática para string)
  public function __toString(): string
  {
    return $this->value->format('Y-m-d H:i:s');
  }
}
