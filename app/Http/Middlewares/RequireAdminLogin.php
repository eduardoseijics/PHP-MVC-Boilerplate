<?php

namespace App\Http\Middlewares;

use Closure;
use App\Http\Request;
use App\Http\Response;
use App\Session\Admin\AuthManager;

class RequireAdminLogin implements MiddlewareInterface
{

  /**
   * Middleware handler
   * @param  Request $request
   * @param  Closure $next
   * @return Response
   */
  public function handle(Request $request, Closure $next): Response
  {
    // Check if the user is logged in
    if (!(new AuthManager)->check()) {
      // If the user isn't logged in, redirect to the login page
      $request->getRouter()->redirect(URL_ADMIN.'/login');
    }

    return $next($request);
  }
}