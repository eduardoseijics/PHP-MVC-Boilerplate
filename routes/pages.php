<?php

use App\Http\Request;
use App\Http\Response;
use App\Controller\Pages\Home;
use App\Controller\Pages\About;
use App\Controller\Pages\TestimonialController;

$obRouter->get('/', [
  function() {
    return new Response(Response::HTTP_OK, Home::getHome());
  }
]);

$obRouter->get('/about', [
  function() {
    return new Response(Response::HTTP_OK, About::getAbout());
  }
]);

$obRouter->get('/testimonials', [
  function(Request $request) {
    return new Response(Response::HTTP_OK, TestimonialController::renderTestimonials($request));
  }
]);

$obRouter->post('/testimonials', [
  function(Request $request) {
    return new Response(Response::HTTP_CREATED, TestimonialController::insertTestimonial($request));
  }
]);