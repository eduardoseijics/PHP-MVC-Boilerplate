<?php

namespace App\Http\Middlewares;

use Closure;
use App\Http\Request;
use App\Security\Csrf;
use App\Http\HttpStatus;
use App\Infrastructure\Http\Response\HtmlResponse;
use App\Infrastructure\Http\Response\JsonResponse;
use App\Infrastructure\Http\Response\Response;

class ValidateCsrfToken implements MiddlewareInterface
{

  /**
   * Handle the middleware
   * @param Request $request
   * @param Closure $next
   * @return Response
   */
  public function handle(Request $request, Closure $next): Response
  {
    // Only validate CSRF token for state-changing methods
    $method = $_SERVER['REQUEST_METHOD'];
    if (!in_array($method, ['POST', 'PUT', 'DELETE'])) {
      return new HtmlResponse(HttpStatus::FORBIDDEN->value, 'Invalid request method');
    }
    
    $token = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
    if (!Csrf::validateToken($token)) {
      return new Response(HttpStatus::FORBIDDEN, 'Invalid csrf token or missing');
    }
    return $next($request);
  }
}
