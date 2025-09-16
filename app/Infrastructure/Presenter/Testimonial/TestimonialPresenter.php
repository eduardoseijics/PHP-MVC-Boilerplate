<?php

namespace App\Infrastructure\Presenter\Testimonial;

use App\Core\View;
use App\Utils\Alert;
use App\Http\Request;
use App\Core\Pagination;
use App\Utils\PaginationRenderer;
use App\Domain\Testimonial\Entity\Testimonial;
use App\Domain\Testimonial\Collection\TestimonialsCollection;

final class TestimonialPresenter
{
  /**
   * Render testimonials page
   * @param TestimonialsCollection $collection
   * @param Pagination $pagination
   * @param ?Request $request
   * @return string
   */
  public function renderTestimonialsPage(TestimonialsCollection $collection, Pagination $pagination, ?Request $request = null): string
  {
    if ($collection->isEmpty()) {
      return $this->renderEmptyTestimonials($pagination, $request);
    }

    return $this->renderTestimonialsList($collection, $pagination, $request);
  }

  /**
   * Creates pagination object for the view
   * @param int @totalItems
   * @param int $currentPage
   * @param int $itemsPerPage
   * @return Pagination
   */
  public function createPagination(int $totalItems, int $currentPage, int $itemsPerPage): Pagination
  {
    return new Pagination($totalItems, $currentPage, $itemsPerPage);
  }

  /**
   * Renders an empty page
   * @param Pagination $pagination
   * @param ?Request $request
   * @return string
   */
  private function renderEmptyTestimonials(Pagination $pagination, ?Request $request = null): string
  {
    return View::render('pages/testimonials/empty', [
      'alert' => Alert::getAlert(),
      'pagination' => $request ? PaginationRenderer::render($request, $pagination) : ''
    ]);
  }

  /**
   * Render testimonials list
   * @param TestimonialCollection $collection
   * @param Pagination $pagination
   * @param ?Request $request
   * @return string
   */
  private function renderTestimonialsList(TestimonialsCollection $collection, Pagination $pagination, ?Request $request = null): string
  {
    $testimonialItems = implode('', array_map(fn($t) => $this->renderTestimonialItem($t), $collection->getArrayCopy()));
      
    return View::render('pages/testimonials/testimonials', [
      'testimonials' => $testimonialItems,
      'alert' => Alert::getAlert(),
      'pagination' => $request ? PaginationRenderer::render($request, $pagination) : ''
    ]);
  }

  /**
   * Render an individual item of a testimonial
   * @param Testimonial $testimonial
   * @return string
   */
  private function renderTestimonialItem(Testimonial $testimonial): string
  {
    return View::render('pages/testimonials/testimonial-item', [
      'name' => $testimonial->name()->value(),
      'message' => $testimonial->message()->value(),
      'date' => $testimonial->date()->display()
    ]);
  }
}
