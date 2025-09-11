<?php

namespace App\Domain\Testimonial\Collection;

use App\Domain\Testimonial\Entity\Testimonial;
use IteratorAggregate;
use ArrayIterator;
use Traversable;

final class TestimonialsCollection implements IteratorAggregate
{
  /** @var Testimonial[] */
  private array $items;

  public function __construct(Testimonial ...$testimonials)
  {
    $this->items = $testimonials;
  }

  /**
   * Add a testimonial to the collection
   * @param Testimonial $testimonial
   * @return void
   */
  public function add(Testimonial $testimonial): void
  {
    $this->items[] = $testimonial;
  }

  /**
   * Get an iterator for the collection
   * @return Traversable
   */
  public function getIterator(): Traversable
  {
    return new ArrayIterator($this->items);
  }

  /**
   * Get the number of testimonials in the collection
   * @return int
   */
  public function count(): int
  {
    return count($this->items);
  }

  /**
   * Slice the collection
   * @param int $offset
   * @param int $length
   * @return TestimonialsCollection
   */
  public function slice(int $offset, int $length): TestimonialsCollection
  {
    $slicedItems = array_slice($this->items, $offset, $length);
    return new TestimonialsCollection(...$slicedItems);
  }

  /**
   * Get array copy of items
   * @return array
   */ 
  public function getArrayCopy(): array
  {
    return $this->items;
  }
}
