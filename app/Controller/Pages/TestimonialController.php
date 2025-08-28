<?php

namespace App\Controller\Pages;

use App\Core\View;
use App\Http\Request;
use App\Core\Pagination;
use App\Model\Entity\Testimonial;
use App\Utils\PaginationRenderer;
use App\Http\RequestDataExtractor;

class TestimonialController extends Page
{
  /**
   * Get testimony content with pagination
   * @param Request $request
   * @return string
   */
  public static function renderTestimonials(Request $request): string
  {
    $obTestimonial     = new Testimonial;
    $totalTestimonials = $obTestimonial->getTestimonialsCount();
    $obRequestData     = new RequestDataExtractor($request);
    $obPagination      = new Pagination($totalTestimonials, $obRequestData->getCurrentPage(), $obRequestData->getItemsPerPage());

    $testimonials = $obTestimonial->getTestimonials(
      order: $obRequestData->getSort() ?? 'date DESC',
      limit: "{$obRequestData->getOffset()}, {$obRequestData->getItemsPerPage()}"
    );

    $layout = implode('', array_map(function ($testimonial) {
      return View::render('pages/testimonials/testimonial-item', [
        'name'    => $testimonial->getName(),
        'message' => $testimonial->getMessage(),
        'date'    => date('d/m/Y H:i:s', strtotime($testimonial->getDate()))
      ]);
    }, $testimonials));

    $content = View::render('pages/testimonials/testimonials', [
      'testimonials' => $layout,
      'pagination' => PaginationRenderer::render($request, $obPagination)
    ]);

    return parent::getPage($content);
  }

  /**
   * Insert a new testimonial
   * @param Request $request
   * @return string
   */
  public static function insertTestimonial(Request $request): string
  {
    try {
      $postVars = $request->getPostVars();
      $obTestimonial = new Testimonial;
      $obTestimonial->setName($postVars['name'])
        ->setMessage($postVars['message'])
        ->setDate(date('Y-m-d H:i:s'))
        ->insert();
      return 'Testimonial inserted successfully';
    } catch (\Exception $e) {
      return 'Error inserting testimonial: ' . $e->getMessage();
    }
  }
}
