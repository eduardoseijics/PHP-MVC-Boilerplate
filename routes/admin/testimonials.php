<?php

use App\Controller\Admin\TestimonialController;
use App\Http\Request;
use App\Http\Response;

$obRouter->get('/admin/testimonials', [
  'middlewares' => [
    'required-admin-login'
  ],
  function(Request $request) {
    return new Response(Response::HTTP_OK, TestimonialController::getTestimonials($request));
  }
]);