<?php

namespace App\Controller\Admin;

use App\Core\View;
use App\Http\Request;
use App\Core\Pagination;
use App\Model\Entity\Testimonial;
use App\Utils\PaginationRenderer;
use App\Http\RequestDataExtractor;

class TestimonialController extends Page {

  /**
   * Get testimonials page
   * @param Request $request
   * @return string
   */
  public static function getTestimonials(Request $request): string
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
      return View::render('admin/modules/testimonials/testimonial-item', [
        'id'      => $testimonial->getId(),
        'name'    => $testimonial->getName(),
        'message' => $testimonial->getMessage(),
        'date'    => date('d/m/Y H:i:s', strtotime($testimonial->getDate()))
      ]);
    }, $testimonials));

    $content = View::render('admin/modules/testimonials/index', [
      'testimonials' => $layout,
      'pagination' => PaginationRenderer::render($request, $obPagination)
    ]);

    return parent::getPanel(content: $content);
  }
}