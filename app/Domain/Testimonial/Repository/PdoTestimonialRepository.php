<?php

namespace App\Domain\Testimonial\Repository;

use PDO;
use App\Core\Database;
use DateTimeImmutable;
use App\Domain\Testimonial\Entity\Testimonial;
use App\Domain\Testimonial\Collection\TestimonialsCollection;
use App\Domain\Testimonial\Entity\ValueObject\TestimonialDate;
use App\Domain\Testimonial\Entity\ValueObject\TestimonialName;
use App\Domain\Testimonial\Entity\ValueObject\TestimonialMessage;

final class PdoTestimonialRepository implements TestimonialRepository
{

  /** @var Database */
  private Database $db;

  /**
   * Constructor
   */
  public function __construct()
  {
    $this->db = new Database('testimonials');
  }

  public function findById(int $id): ?Testimonial
  {
    $stmt = $this->db->select('id = ?', [$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
      return null;
    }

    return $this->hydrate($row);
  }

  /**
   * Save a testimonial
   * @param Testimonial $testimonial
   * @return void
   */
  public function save(Testimonial $testimonial): int
  {
    return $this->db->insert([
      'name'    => $testimonial->name()->value(),
      'message' => $testimonial->message()->value(),
      'date'    => $testimonial->date()->value()->format('Y-m-d H:i:s')
    ]);
  }

  /**
   * Update a testimonial
   * @param Testimonial $testimonial
   * @return bool
   */
  public function update(Testimonial $testimonial): bool
  {
    $data = [
      'name'    => $testimonial->name()->value(),
      'message' => $testimonial->message()->value(),
    ];

    $where = 'id = ?';
    $params = [$testimonial->id()];

    $affectedRows = $this->db->update($where, $data, $params);

    return $affectedRows > 0;
  }

  /**
   * Get all testimonials
   * @return TestimonialsCollection
   */
  public function all(): TestimonialsCollection
  {
    $stmt = $this->db->select();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return new TestimonialsCollection(...array_map([$this, 'hydrate'], $rows));
  }

  /**
   * Get paginated testimonials
   * @param int $offset
   * @param int $limit
   * @param string $order
   * @return TestimonialsCollection
   */
  public function paginated(int $offset, int $limit, ?string $order = 'date DESC'): TestimonialsCollection
  {
    $stmt = $this->db->select(order: $order, limit: "$offset, $limit");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return new TestimonialsCollection(...array_map([$this, 'hydrate'], $rows));
  }

  /**
   * Count the number of testimonials
   * @return int
   */
  public function count(): int
  {
    $stmt = $this->db->select(fields: 'COUNT(*) as total');
    return (int) $stmt->fetchObject()->total;
  }

  /**
   * Hydrate a testimonial from a database row
   * @param array $row
   * @return Testimonial
   */
  private function hydrate(array $row): Testimonial
  {
    return new Testimonial(
      id     : (int) $row['id'],
      name   : new TestimonialName($row['name']),
      message: new TestimonialMessage($row['message']),
      date   : new TestimonialDate(new DateTimeImmutable($row['date']))
    );
  }
}
