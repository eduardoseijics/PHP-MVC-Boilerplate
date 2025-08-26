<?php

namespace App\Http;

class RequestDataExtractor
{

  /**
   * Array of filters for the query
   * @var array<string, mixed>
   */
  private array $filters = [];

  /**
   * Sort order for the query
   * @var string|null
   */
  private ?string $sort = null;

  /**
   * Current page for the query
   * @var int
   */
  private int $currentPage = 1;

  /**
   * Items per page for the query
   * @var int
   */
  private int $itemsPerPage = 5;

  public function __construct(Request $request)
  {
    $this->extractQueryParams($request);
  }

  /**
   * Extract query parameters from the request and populate the class properties
   * @param Request $request
   * @return void
   */
  private function extractQueryParams(Request $request): void
  {
    $query = $request->getQueryParams();

    // Order
    $this->sort = $query['sort'] ?? null;
    unset($query['sort']);

    // Current page
    $this->currentPage = isset($query['page']) ? max(1, (int)$query['page']) : 1;
    unset($query['page']);

    // Items per page
    $this->itemsPerPage = isset($query['itemsPerPage']) ? max(1, (int)$query['itemsPerPage']) : 5;
    unset($query['itemsPerPage']);

    // 
    $this->filters = $query;
  }

  /**
   * Get sort
   */
  public function getSort(): ?string
  {
    return $this->sort;
  }

  /**
   * Get filters
   */
  public function getFilters(): array
  {
    return $this->filters;
  }

  /**
   * Get current page
   */
  public function getCurrentPage(): int
  {
    return $this->currentPage;
  }

  /**
   * Get items per page
   */
  public function getItemsPerPage(): int
  {
    return $this->itemsPerPage;
  }

  /**
   * Returns the offset for paginated queries
   */
  public function getOffset(): int
  {
    return ($this->currentPage - 1) * $this->itemsPerPage;
  }
}
