<?php

use App\Http\Response;
use App\Controller\Pages\Home;

//ROTA HOME
$obRouter->get('/', [
  function() {
    return new Response(Response::HTTP_OK, Home::getHome());
  }
]);