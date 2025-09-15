<?php

namespace App\Http;

use DI\Container;
use ReflectionMethod;

class ControllerInvoker
{

  /**
   * @var callable The callable to be invoked
   */
  private $callable;

  public function __construct(private Container $container, private array $route)
  {
    $this->callable = $this->createCallable();
  }

  /**
   * Create a callable for the controller action
   * @return callable
   */
  private function createCallable(): callable
  {
    $controllerCallable = $this->route['controller'];

    if (is_array($controllerCallable)) {
      $instance = $this->container->get($controllerCallable[0]);
      $reflection = new ReflectionMethod($instance, $controllerCallable[1]);

      $args = [];
      foreach ($reflection->getParameters() as $param) {
        $type = $param->getType()?->getName();
        $args[] = match ($type) {
          Request::class => $this->route['variables']['request'] ?? null,
          default => $this->route['variables'][$param->getName()] ?? null,
        };
      }

      return fn() => $instance->{$controllerCallable[1]}(...$args);
    }

    return $controllerCallable;
  }

  /**
   * Get the callable to be invoked
   * @return callable
   */
  public function getCallable(): callable
  {
    return $this->callable;
  }
}
