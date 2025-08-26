<?php

namespace App\Model\Entity;

use PDO;
use Exception;
use App\Core\Database;
use App\Http\Response;

class Testimonial
{

  /**
   * Testimonial ID
   * @var int
   */
  public $id;

  /**
   * Testimonial Name
   * @var string
   */
  public $name;

  /**
   * Testimonial Message
   * @var string
   */
  public $message;

  /**
   * Testimonial Date
   * @var string
   */
  public $date;

  /**
   * Get the testimonial ID
   * @return int
   */
  public function getId(): int
  {
    return $this->id;
  }

  /**
   * Get the testimonial name
   * @return string
   */
  public function getName(): string
  {
    return $this->name;
  }

  /**
   * Set the testimonial name
   * @param string $name
   * @return self
   */
  public function setName(string $name): self
  {
    $this->name = $name;
    return $this;
  }

  /**
   * Get the testimonial message
   * @return string
   */
  public function getMessage(): string
  {
    return $this->message;
  }

  /**
   * Set the testimonial message
   * @param string $message
   * @return self
   */
  public function setMessage(string $message): self
  {
    $this->message = $message;
    return $this;
  }

  /**
   * Get the testimonial date
   * @return string
   */
  public function getDate(): string
  {
    return $this->date;
  }

  /**
   * Set the testimonial date
   * @param string $date
   * @return self
   */
  public function setDate(string $date): self
  {
    $this->date = $date;
    return $this;
  }

  /**
   * Get all testimonials
   * @return array<Testimonial>
   */
  public static function getTestimonials($where = null, $params = [], $order = null, $limit = null, $fields = '*'): array
  {
    $obDatabaseTestimonial = new Database('testimonials');
    return $obDatabaseTestimonial->select($where, $params, $fields, $order, $limit)->fetchAll(PDO::FETCH_CLASS, Testimonial::class);
  }

  /**
   * Insert a new testimonial
   * @return bool
   */
  public function insert(): bool
  {
    $obDatabaseTestimonial = new Database('testimonials');
    $obDatabaseTestimonial->insert([
      'name'    => $this->name,
      'message' => $this->message,
      'date'    => $this->date
    ]);
    return true;
  }

  /**
   * Get the total number of testimonials
   * @return int
   */
  public function getTestimonialsCount(): int
  {
    $obDatabaseTestimonial = new Database('testimonials');
    return (int) $obDatabaseTestimonial
      ->select(fields: 'COUNT(*) as total')
      ->fetchObject()
      ->total;
  }
}
