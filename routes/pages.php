<?php

use App\Http\Request;
use App\Http\Response;
use App\Controller\Pages\Home;
use App\Controller\Pages\About;
use App\Controller\Pages\TestimonialController;

$obRouter->get('/', [
  'middlewares' => ['maintenance'],
  'controller' => [Home::class, 'getHome']
]);

$obRouter->get('/about', [
  'controller' => [About::class, 'getAbout']
]);

$obRouter->get('/testimonials', [
  'controller' => [TestimonialController::class, 'renderTestimonials']
]);

$obRouter->post('/testimonials', [
  'controller' => [TestimonialController::class, 'createTestimonial']
]);

$obRouter->get('/testimonials/{id}/{hash}', [
  'controller' => [TestimonialController::class, 'getById']
]);
