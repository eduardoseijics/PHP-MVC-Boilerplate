<?php

namespace App\Http\Middlewares;

use Closure;
use App\Http\Request;
use App\Http\Response;
use App\Security\Csrf;

class ValidateCsrfToken implements MiddlewareInterface
{
  public function handle(Request $request, Closure $next): Response
  {
    // Only validate CSRF token for state-changing methods
    $method = $_SERVER['REQUEST_METHOD'];
    if (!in_array($method, ['POST', 'PUT', 'DELETE'])) {
      return new Response(Response::HTTP_FORBIDDEN, 'Invalid request method');
    }
    
    $token = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
    if (!Csrf::validateToken($token)) {
      return new Response(Response::HTTP_FORBIDDEN, 'Invalid csrf token or missing');
    }
    return $next($request);
  }
}
