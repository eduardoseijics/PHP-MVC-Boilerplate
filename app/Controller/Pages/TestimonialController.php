<?php

namespace App\Controller\Pages;

use App\Core\View;
use App\Utils\Alert;
use App\Http\Request;
use App\Core\Pagination;
use App\Utils\PaginationRenderer;
use App\Http\RequestDataExtractor;
use App\Domain\Testimonial\Service\CreateTestimonialService;
use App\Domain\Testimonial\Collection\TestimonialsCollection;
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

  public function renderTestimonials(Request $request): string
  {
    $extractor = new RequestDataExtractor($request);
    $currentPage  = $extractor->getCurrentPage();
    $itemsPerPage = $extractor->getItemsPerPage();
    $sort         = $extractor->getSort() ?? 'date DESC';
    $offset       = $extractor->getOffset();

    $pagination = $this->buildPagination($currentPage, $itemsPerPage);
    $collection = $this->getTestimonials($offset, $itemsPerPage, $sort);

    // Redireciona se página inválida
    if ($collection->isEmpty() && $currentPage > 1) {
      $request->getRouter()->redirect('/testimonials');
    }

    // Renderiza view vazia se não houver testimonials
    if ($collection->isEmpty()) {
      return $this->renderEmptyTestimonials($request, $pagination);
    }

    // Renderiza testimonials existentes
    return $this->renderTestimonialsList($request, $collection, $pagination);
  }

  /**
   * Cria objeto de paginação
   */
  private function buildPagination(int $currentPage, int $itemsPerPage): Pagination
  {
    $totalTestimonials = $this->repository->count();
    return new Pagination($totalTestimonials, $currentPage, $itemsPerPage);
  }

  /**
   * Busca testimonials paginados
   */
  private function getTestimonials(int $offset, int $limit, string $sort): TestimonialsCollection
  {
    return $this->repository->paginated($offset, $limit, $sort);
  }

  /**
   * Renderiza a view quando não há testimonials
   */
  private function renderEmptyTestimonials(Request $request, Pagination $pagination): string
  {
    $content = View::render('pages/testimonials/empty', [
      'alert' => Alert::getAlert(),
      'pagination' => PaginationRenderer::render($request, $pagination)
    ]);

    return parent::getPage($content);
  }

  /**
   * Renderiza a lista de testimonials
   */
  private function renderTestimonialsList(Request $request, TestimonialsCollection $collection, Pagination $pagination): string
  {
    $testimonialItems = implode('', array_map(fn($t) => View::render('pages/testimonials/testimonial-item', [
      'name'    => $t->name()->value(),
      'message' => $t->message()->value(),
      'date'    => $t->date()->value()->format('d/m/Y H:i:s')
    ]), $collection->getArrayCopy()));

    $content = View::render('pages/testimonials/testimonials', [
      'testimonials' => $testimonialItems,
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

  public function getById($id) {}
}
