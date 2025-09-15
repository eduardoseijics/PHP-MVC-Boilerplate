<?php

namespace App\Domain\Testimonial\Service;

use App\Domain\Testimonial\Entity\Testimonial;
use App\Domain\Testimonial\Entity\ValueObject\TestimonialName;
use App\Domain\Testimonial\Repository\PdoTestimonialRepository;
use App\Domain\Testimonial\Entity\ValueObject\TestimonialMessage;

class UpdateTestimonialService
{
  public function __construct(private PdoTestimonialRepository $repository) {}

  /**
   * Update an existing testimonial
   * @param string $author
   * @param string $message
   * @return bool
   */
  public function execute(int $id, string $author, string $message): bool
  {
    $testimonial = $this->repository->findById($id);

    if (!$testimonial) {
      throw new \RuntimeException('Testimonial not found');
    }

    $updated = new Testimonial(
      $testimonial->id(),
      new TestimonialName($author),
      new TestimonialMessage($message),
      $testimonial->date()
    );

    return $this->repository->update($updated);
  }
}
