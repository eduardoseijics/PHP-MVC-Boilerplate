<?php

namespace App\Http\Middlewares;

use App\Http\Request;
use App\Http\Response;
use Closure;
use App\Session\Admin\Login as LoginSession;

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
    if (!LoginSession::isLogged()) {
      // If the user isn't logged in, redirect to the login page
      $request->getRouter()->redirect('/admin/login');
    }

    return $next($request);
  }
}