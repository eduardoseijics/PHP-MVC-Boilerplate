<?php

namespace App\Domain\Testimonial\Repository;

use App\Domain\Testimonial\Entity\Testimonial;
use App\Domain\Testimonial\Collection\TestimonialsCollection;

interface TestimonialRepository
{
  public function save(Testimonial $testimonial): void;
  public function all(): TestimonialsCollection;
  public function count(): int;
}
