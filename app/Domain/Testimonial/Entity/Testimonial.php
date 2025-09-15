<?php

namespace App\Domain\Testimonial\Entity;

use App\Domain\Testimonial\Entity\ValueObject\TestimonialDate;
use App\Domain\Testimonial\Entity\ValueObject\TestimonialName;
use App\Domain\Testimonial\Entity\ValueObject\TestimonialMessage;

final class Testimonial
{
  private function __construct(
    private ?int $id = null,
    private TestimonialName $name,
    private TestimonialMessage $message,
    private TestimonialDate $date
  ) {}

  /**
   * Get the value of id
   * @return int|null
   */
  public function id(): ?int
  {
    return $this->id;
  }

  /**
   * Get the value of name
   * @return TestimonialName
   */
  public function name(): TestimonialName
  {
    return $this->name;
  }

  /**
   * Get the value of message
   * @return TestimonialMessage
   */
  public function message(): TestimonialMessage
  {
    return $this->message;
  }

  /**
   * Get the value of date
   * @return TestimonialDate
   */
  public function date(): TestimonialDate
  {
    return $this->date;
  }

  /**
   * Summarize the testimonial message to 50 characters
   * @return string
   */
  public function summarize(): string
  {
    return mb_strimwidth($this->message()->value(), 0, 50, '...');
  }

  /**
   * Check if the testimonial was written in the last 7 days
   * @return bool
   */
  public function wasWrittenRecently(): bool
  {
    return $this->date()->value() > new \DateTimeImmutable('-7 days');
  }
}
