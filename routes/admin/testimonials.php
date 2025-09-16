<?php

use App\Controller\Admin\TestimonialController;
use App\Http\Request;
use App\Http\Response;

$obRouter->get('/admin/testimonials', [
  'middlewares' => ['required-admin-login'],
  'controller' => [TestimonialController::class, 'getTestimonials']
]);

