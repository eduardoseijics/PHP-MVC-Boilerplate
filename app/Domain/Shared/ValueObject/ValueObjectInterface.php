<?php

namespace App\Domain\Shared\ValueObject;

interface ValueObjectInterface
{
  public function value(): mixed;
  public function equals(ValueObjectInterface $other): bool;
}