<?php

namespace App\Controller\Admin;

use App\Core\View;
use App\Utils\Alert;
use App\Http\Request;
use App\Core\Pagination;
use App\Utils\PaginationRenderer;
use App\Http\RequestDataExtractor;
use App\Domain\Testimonial\Service\CreateTestimonialService;
use App\Domain\Testimonial\Repository\PdoTestimonialRepository;

class TestimonialController extends Page
{
  
  private CreateTestimonialService $service;
  private PdoTestimonialRepository $repository;

  public function __construct()
  {
    $this->repository = new PdoTestimonialRepository();
    $this->service = new CreateTestimonialService($this->repository);
  }

  /**
   * Get testimonials page
   * @param Request $request
   * @return string
   */
  public static function getTestimonials(Request $request): string
  {
    $repository        = new PdoTestimonialRepository;
    $totalTestimonials = $repository->count();
    $extractor         = new RequestDataExtractor($request);
    
    $currentPage  = $extractor->getCurrentPage();
    $itemsPerPage = $extractor->getItemsPerPage();
    $sort         = $extractor->getSort();
    $offset       = $extractor->getOffset();
    
    $pagination        = new Pagination($totalTestimonials, $extractor->getCurrentPage(), $extractor->getItemsPerPage());
    $testimonials = $repository->paginated($offset, $itemsPerPage, $sort);

    $layout = implode('', array_map(function ($testimonial) {
      return View::render('admin/modules/testimonials/testimonial-item', [
        'id'      => $testimonial->id()->value(),
        'name'    => $testimonial->name()->value(),
        'message' => $testimonial->message()->value(),
        'date'    => $testimonial->date()->value()->format('d/m/Y H:i:s') ?? ''
      ]);
    }, $testimonials->getArrayCopy()));

    $content = View::render('admin/modules/testimonials/index', [
      'testimonials' => $layout,
      'pagination' => PaginationRenderer::render($request, $pagination)
    ]);

    return parent::getPanel(title: 'Admin | Testimonials', content: $content);
  }
}
