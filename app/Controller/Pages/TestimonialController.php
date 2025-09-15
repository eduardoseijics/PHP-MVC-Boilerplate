<?php

namespace App\Controller\Pages;

use App\Core\View;
use App\Utils\Alert;
use App\Http\Request;
use App\Core\Pagination;
use App\Utils\PaginationRenderer;
use App\Http\RequestDataExtractor;
use App\Domain\Testimonial\Service\CreateTestimonialService;
use App\Domain\Testimonial\Repository\PdoTestimonialRepository;

final class TestimonialController extends Page
{
  private CreateTestimonialService $service;
  private PdoTestimonialRepository $repository;

  /**
   * Constructor
   * @param PdoTestimonialRepository $repository
   * @param CreateTestimonialService $service
   */
  public function __construct(PdoTestimonialRepository $repository, CreateTestimonialService $service)
  {
    $this->repository = $repository;
    $this->service = $service;
  }

  /**
   * Render the testimonials page
   * @param Request $request
   * @return string
   */
  public function renderTestimonials(Request $request): string
  {
    $extractor = new RequestDataExtractor($request);

    $currentPage  = $extractor->getCurrentPage();
    $itemsPerPage = $extractor->getItemsPerPage();
    $sort         = $extractor->getSort() ?? 'date DESC';
    $offset       = $extractor->getOffset();

    $totalTestimonials = $this->repository->count();
    $pagination = new Pagination($totalTestimonials, $currentPage, $itemsPerPage);

    $collection = $this->repository->paginated($offset, $itemsPerPage, $sort);
    $layout = '';
    foreach ($collection as $testimonial) {
      $layout .= View::render('pages/testimonials/testimonial-item', [
        'name'    => $testimonial->name()->value(),
        'message' => $testimonial->message()->value(),
        'date'    => $testimonial->date()->value()->format('d/m/Y H:i:s')
      ]);
    }

    $content = View::render('pages/testimonials/testimonials', [
      'testimonials' => $layout,
      'alert' => Alert::getAlert(),
      'pagination' => PaginationRenderer::render($request, $pagination)
    ]);

    return parent::getPage($content);
  }

  /**
   * Insert a new testimonial
   * @param Request $request
   * @return string
   */
  public function insertTestimonial(Request $request): null|string
  {
    try {
      $this->service->execute(
        $request->getPostParam('name'),
        $request->getPostParam('message')
      );
      Alert::success('Testimonial inserted with success!');
      return $request->getRouter()->redirect('/testimonials');
    } catch (\Exception $e) {
      return 'Error inserting testimonial: ' . $e->getMessage();
    }
  }

  public function getById($id)
  {
  }
}
