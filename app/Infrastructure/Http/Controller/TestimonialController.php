<?php

namespace App\Infrastructure\Http\Controller;

use App\Controller\Pages\Page;
use Exception;
use App\Utils\Alert;
use App\Http\Request;
use App\Http\RequestDataExtractor;
use App\Infrastructure\Http\Response\HtmlResponse;
use App\Infrastructure\Http\Response\RedirectResponse;
use App\Domain\Testimonial\Service\CreateTestimonialService;
use App\Infrastructure\Presenter\Testimonial\TestimonialPresenter;
use App\Infrastructure\Persistence\Testimonial\PdoTestimonialRepository;

final class TestimonialController extends Page
{
  private CreateTestimonialService $service;
  private PdoTestimonialRepository $repository;
  private TestimonialPresenter $presenter;

  public function __construct(
    PdoTestimonialRepository $repository,
    CreateTestimonialService $service,
    TestimonialPresenter $presenter
  ) {
    $this->repository = $repository;
    $this->service = $service;
    $this->presenter = $presenter;
  }

  /**
   * Render paginated testimonials
   * @param Request $request
   * @return HtmlResponse|RedirectResponse
   */
  public function renderTestimonials(Request $request): HtmlResponse|RedirectResponse
  {
    $extractor = new RequestDataExtractor($request);
    $collection = $this->repository->paginated(
      $extractor->getOffset(),
      $extractor->getItemsPerPage(),
      $extractor->getSort()
    );

    $pagination = $this->presenter->createPagination(
      $collection->count(),
      $extractor->getCurrentPage(),
      $extractor->getItemsPerPage()
    );

    if ($collection->isEmpty() && $extractor->getCurrentPage() > 1) {
      return new RedirectResponse('/testimonials');
    }
    $content = $this->presenter->renderTestimonialsPage($collection, $pagination, $request);

    return new HtmlResponse(parent::getPage($content));
  }

  /**
   * Insert new testimonial
   * @param Request $request
   * @return RedirectResponse|HtmlResponse
   */
  public function insertTestimonial(Request $request): RedirectResponse|HtmlResponse
  {
    try {
      $this->service->execute(
        $request->getPostParam('name'),
        $request->getPostParam('message')
      );
      Alert::success('Testimonial inserted with success!');
      return new RedirectResponse('/testimonials');
    } catch (\Exception $e) {
      return new HtmlResponse('Error inserting testimonial: ' . $e->getMessage());
    }
  }

  /**
   * Obtain an testimonial by ID
   * @param int $id
   * @return HtmlResponse
   */
  public function getById(int $id): HtmlResponse
  {
    // Aqui você pode chamar repository + presenter
    return new HtmlResponse('Not implemented yet.');
  }
}
