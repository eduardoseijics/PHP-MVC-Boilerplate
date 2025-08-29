<?php

namespace App\Http\Middlewares;

use Closure;
use Exception;
use App\Http\Request;
use App\Http\Response;

/**
 * Represents the queue of middlewares to be executed.
 */
class Queue
{
  /**
   * Default middlewares applied to all routes.
   * @var array<string>
   */
  private static array $defaultMiddlewares = [];

  /**
   * Middlewares to be executed in the current queue.
   * @var array<string>
   */
  private array $middlewares = [];

  /**
   * The controller action to be executed at the end of the queue.
   * @var Closure
   */
  private Closure $controller;

  /**
   * Arguments to pass to the controller.
   * @var array
   */
  private array $controllerArgs = [];

  /**
   * Map of all available middlewares (name => class).
   * @var array<string, string>
   */
  private static array $map = [];

  /**
   * Queue constructor.
   * @param array<string> $middlewares
   * @param Closure $controller
   * @param array $controllerArgs
   */
  public function __construct(array $middlewares, Closure $controller, array $controllerArgs = [])
  {
    $this->middlewares = array_merge(self::$defaultMiddlewares, $middlewares);
    $this->controller = $controller;
    $this->controllerArgs = $controllerArgs;
  }

  /**
   * Executes the next middleware in the queue or the controller if the queue is empty.
   * @param Request $request
   * @return Response
   * @throws Exception
   */
  public function next(Request $request): Response
  {
    // If the middleware queue is empty, execute the controller.
    if (empty($this->middlewares)) {
      return $this->runController();
    }

    // Get the name of the next middleware.
    $middleware = array_shift($this->middlewares);

    // Resolve the middleware instance and execute the handle method.
    $instance = $this->resolveMiddleware($middleware);

    return $instance->handle($request, fn(Request $req) => $this->next($req));
  }

  /**
   * Resolves and returns a middleware instance.
   * @param string $middlewareName
   * @return MiddlewareInterface
   * @throws Exception
   */
  private function resolveMiddleware(string $middlewareName): MiddlewareInterface
  {
    if (!isset(self::$map[$middlewareName])) {
      throw new Exception("Middleware '{$middlewareName}' not defined in the map.");
    }

    $middlewareClass = self::$map[$middlewareName];

    if (!class_exists($middlewareClass)) {
      throw new Exception("Middleware class '{$middlewareClass}' not found.");
    }

    $instance = new $middlewareClass();

    if (!$instance instanceof MiddlewareInterface) {
      throw new Exception("Class '{$middlewareClass}' must implement MiddlewareInterface.");
    }

    return $instance;
  }

  /**
   * Executes the controller action and ensures the response is a Response object.
   * @return Response
   */
  private function runController(): Response
  {
    $result = ($this->controller)(...$this->controllerArgs);

    return $result instanceof Response
      ? $result
      : new Response(Response::HTTP_OK, (string) $result);
  }

  /**
   * Sets the map of available middlewares.
   * @param array<string, string> $map
   * @return void
   * @throws Exception
   */
  public static function setMap(array $map): void
  {
    foreach ($map as $name => $class) {
      if (!is_string($name) || !is_string($class)) {
        throw new Exception('Invalid middleware map format.');
      }
    }

    self::$map = $map;
  }

  /**
   * Sets the default middlewares.
   * @param array<string> $defaultMiddlewares
   * @return void
   */
  public static function setDefaultMiddlewares(array $defaultMiddlewares): void
  {
    self::$defaultMiddlewares = $defaultMiddlewares;
  }
}
