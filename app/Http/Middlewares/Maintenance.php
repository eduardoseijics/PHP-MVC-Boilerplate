<?php

namespace App\Http\Middlewares;

use Closure;
use App\Http\Request;
use App\Http\Response;

class Maintenance implements MiddlewareInterface{

  /**
   * Handle the middleware
   * @param Request $request
   * @param Closure $next
   * @return Response
   */
  public function handle(Request $request, Closure $next): Response
  {
    if(getenv('MAINTENANCE_MODE') === 'true'){
      // If the request is not for the admin area, show maintenance message
      if(strpos($request->getUri(), URL.'/admin') === false){
        $content = '<h1>Site Under Maintenance</h1><p>We are currently performing scheduled maintenance. Please check back later.</p>';
        return new Response(Response::HTTP_SERVICE_UNAVAILABLE, $content);
      }
    }
    return $next($request);
  }
}