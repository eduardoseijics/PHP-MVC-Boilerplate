<?php

namespace App\Utils;

use App\Http\Request;
use App\Core\Pagination;
use App\Http\RequestDataExtractor;
use App\Domain\Testimonial\Repository\PdoTestimonialRepository;

final class PaginationFactory
{
  public function __construct(private PdoTestimonialRepository $repository) {}

  public function createFromRequest(Request $request): array
  {
    $extractor = new RequestDataExtractor($request);

    $collection = $this->repository->paginated(
      $extractor->getOffset(),
      $extractor->getItemsPerPage(),
      $extractor->getSort() ?? 'date DESC'
    );

    $pagination = new Pagination(
      $this->repository->count(),
      $extractor->getCurrentPage(),
      $extractor->getItemsPerPage()
    );

    return [$collection, $pagination];
  }
}
