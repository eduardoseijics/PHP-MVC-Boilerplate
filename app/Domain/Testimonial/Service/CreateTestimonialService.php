<?php

namespace App\Domain\Testimonial\Service;

use App\Domain\Testimonial\Entity\Testimonial;
use App\Domain\Testimonial\Entity\ValueObject\TestimonialDate;
use App\Domain\Testimonial\Entity\ValueObject\TestimonialName;
use App\Domain\Testimonial\Repository\PdoTestimonialRepository;
use App\Domain\Testimonial\Entity\ValueObject\TestimonialMessage;

final class CreateTestimonialService
{

  public function __construct(private PdoTestimonialRepository $repository) {}

  /**
   * Create a new testimonial and save it to the repository
   * @param string $name
   * @param string $message
   * @return void
   */
  public function execute(string $name, string $message): void
  {
    $testimonial = new Testimonial(
      new TestimonialName($name),
      new TestimonialMessage($message),
      new TestimonialDate(new \DateTimeImmutable())
    );

    $this->repository->save($testimonial);
  }
}
