<?php

use App\Controller\Pages\About;
use App\Http\Response;
use App\Controller\Pages\Home;

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


$obRouter->get('/page/{idPage}', [
  function($idPage) {
    return new Response(Response::HTTP_OK, $idPage);
  }
]);