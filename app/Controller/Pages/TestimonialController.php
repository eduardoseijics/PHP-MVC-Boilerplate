<?php

namespace App\Controller\Pages;

use App\Core\View;
use App\Http\Request;
use App\Model\Entity\Testimonial;

class TestimonialController extends Page
{

  /**
   * Get testimony content
   * @return string
   */
  public static function renderTestimonials(): string
  {

    $obTestimonial = new Testimonial;
    $testimonials =  $obTestimonial->getTestimonials(order: 'id DESC');

    $layout = '';
    foreach ($testimonials as $testimonial) {
      $layout .= View::render('pages/testimonials/testimonial-item', [
        'name'    => $testimonial->getName(),
        'message' => $testimonial->getMessage(),
        'date'    => date('d/m/Y H:i:s', strtotime($testimonial->getDate()))
      ]);
    }

    $content = View::render('pages/testimonials/testimonials', [
      'testimonials' => $layout
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
