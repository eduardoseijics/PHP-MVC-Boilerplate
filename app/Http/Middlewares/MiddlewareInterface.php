<?php

namespace App\Http\Middlewares;

use Closure;
use App\Http\Request;
use App\Infrastructure\Http\Response\Response;

/**
 * Interface to ensure all middlewares follow a consistent contract.
 */
interface MiddlewareInterface
{
  /**
   * Handle a request and pass it to the next middleware or controller.
   * @param Request $request
   * @param Closure $next
   * @return Response
   */
  public function handle(Request $request, Closure $next): Response;
}