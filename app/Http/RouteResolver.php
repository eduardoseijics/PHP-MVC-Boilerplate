<?php

namespace App\Http;

use Exception;

class RouteResolver
{
  public function __construct(private array $routes, private Request $request) {}

  /**
   * Resolve the current route and return its configuration
   * @return array
   */
  public function resolve(): array
  {
    $uri = $this->request->getUri();
    $method = $this->request->getHttpMethod();
    foreach ($this->routes as $pattern => $methods) {
      if (preg_match($pattern, $uri, $matches)) {
        if (!isset($methods[$method])) {
          throw new Exception('Method not allowed', Response::HTTP_METHOD_NOT_ALLOWED);
        }

        unset($matches[0]);
        $keys = $methods[$method]['variables'];
        $methods[$method]['variables'] = array_combine($keys, $matches) ?: [];
        $methods[$method]['variables']['request'] = $this->request;

        if (!isset($methods[$method]['controller'])) {
          throw new Exception('Controller não definido para esta rota', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $methods[$method];
      }
    }

    throw new Exception(PageNotFound::get404('site'), Response::HTTP_NOT_FOUND);
  }
}
