<?php

use App\Controller\Pages\Home;
use App\Controller\Pages\About;
use App\Infrastructure\Http\Controller\TestimonialController;

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
  'controller' => [TestimonialController::class, 'insertTestimonial']
]);

$obRouter->get('/testimonials/{id}/{hash}', [
  'controller' => [TestimonialController::class, 'getById']
]);
