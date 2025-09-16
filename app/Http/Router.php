<?php

namespace App\Http;

use Closure;
use Exception;
use App\Http\Request;
use ReflectionMethod;
use App\Http\PageNotFound;
use App\Http\Middlewares\Queue as MiddlewareQueue;
use App\Infrastructure\Http\Response\HtmlResponse;
use App\Infrastructure\Http\Response\JsonResponse;
use App\Infrastructure\Http\Response\Response;
use Psr\Container\ContainerInterface;

class Router
{

  private string $url = '';
  private string $prefix = '';
  private string $package = '';
  private array $routes = [];
  private Request $request;
  private ContainerInterface $container;

  /**
   * Constructor
   * @param string $url
   * @param ContainerInterface $container
   */
  public function __construct(string $url, ContainerInterface $container)
  {
    $this->url = $url;
    $this->container = $container;
    $this->request = new Request($this);
    $this->setPrefix();
    $this->setPackage();
  }

  /**
   * Set the prefix based on the URL
   * @return void
   */
  private function setPrefix(): void
  {
    $parseUrl = parse_url($this->url);
    $this->prefix = $parseUrl['path'] ?? '';
  }

  /**
   * Set the package based on the URI
   * @return void
   */
  public function setPackage(): void
  {
    $xUri = explode('/', $this->getUri());
    unset($xUri[0]);
    $package = current($xUri);
    if (empty($package)) $package = 'site';
    $this->package = $package;
  }

  /**
   * Add a route to the router
   * @param string $method
   * @param string $route
   * @param array $params
   * @return void
   */
  private function addRoute(string $method, string $route, array $params = []): void
  {
    foreach ($params as $key => $value) {
      if ($value instanceof Closure) {
        $params['controller'] = $value;
        unset($params[$key]);
        continue;
      }
    }

    $params['middlewares'] = $params['middlewares'] ?? [];
    $params['variables'] = [];

    if (preg_match_all('/{(.*?)}/', $route, $matches)) {
      $route = preg_replace('/{(.*?)}/', '(.*?)', $route);
      $params['variables'] = $matches[1];
    }

    $patternRoute = '/^' . str_replace('/', '\/', $route) . '$/';
    $this->routes[$patternRoute][$method] = $params;
  }

  /**
   * Define a GET route
   * @param string $route
   * @param array $params
   * @return void
   */
  public function get(string $route, array $params = []): void
  {
    $this->addRoute('GET', $route, $params);
  }

  /**
   * Define a POST route
   * @param string $route
   * @param array $params
   * @return void
   */
  public function post(string $route, array $params = []): void
  {
    $this->addRoute('POST', $route, $params);
  }

  /**
   * Get the URI of the request, removing the prefix
   * @return string
   */
  private function getUri(): string
  {
    $uri = $this->request->getUri();
    $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

    return end($xUri);
  }

  /**
   * Resolve the current route
   * @return array
   * @throws Exception
   */
  private function getRoute(): array
  {
    $uri = $this->getUri();
    $httpMethod = $this->request->getHttpMethod();
    foreach ($this->routes as $patternRoute => $methods) {
      if (preg_match($patternRoute, $uri, $matches)) {
        if (isset($methods[$httpMethod])) {
          unset($matches[0]);
          $keys = $methods[$httpMethod]['variables'];
          $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
          $methods[$httpMethod]['variables']['request'] = $this->request;
          return $methods[$httpMethod];
        }
        throw new Exception('Method not allowed', HttpStatus::METHOD_NOT_ALLOWED->value);
      }
    }
    throw new Exception(PageNotFound::get404($this->package), HttpStatus::NOT_FOUND->value);
  }

  /**
   * Run the router
   * @return Response
   */
  public function run(): Response
  {
    try {
      $route = $this->getRoute();
      
      if (!isset($route['controller'])) {
        throw new Exception('A URL não pode ser processada', HttpStatus::INTERNAL_SERVER_ERROR->value);
      }

      $controllerCallable = $route['controller'];

      // Se for array [Controller::class, 'metodo']
      if (is_array($controllerCallable)) {
        $instance = $this->container->get($controllerCallable[0]);
        $method = $controllerCallable[1];

        $reflection = new ReflectionMethod($instance, $method);
        $args = [];

        foreach ($reflection->getParameters() as $param) {
          $paramType = $param->getType();

          // 1. Sem type hint -> pega de variables
          if (!$paramType) {
            $args[] = $route['variables'][$param->getName()] ?? null;
            continue;
          }

          $paramClass = $paramType->getName();

          // 2. Se for Request
          if ($paramClass === Request::class) {
            $args[] = $this->request;
            continue;
          }

          // 3. Tipos primitivos
          if (in_array($paramClass, ['string', 'int', 'float', 'bool'])) {
            $value = $route['variables'][$param->getName()] ?? null;

            if ($value !== null) {
              settype($value, $paramClass);
            }

            $args[] = $value;
            continue;
          }

          // 4. Classes -> Container
          $args[] = $this->container->get($paramClass);
        }

        $controllerCallable = fn() => $instance->{$method}(...$args);
      }

      return (new MiddlewareQueue($route['middlewares'], $controllerCallable))
        ->next($this->request);
    } catch (Exception $e) {
      return new HtmlResponse( $e->getMessage(), HttpStatus::INTERNAL_SERVER_ERROR->value);
    }
  }

  /**
   * Get the current URL of the request
   * @return string
   */
  public function getCurrentUrl(): string
  {
    return $this->url . $this->getUri();
  }

  /**
   * Redirect to another route
   * @param string $route
   * @return void
   */
  public function redirect(string $route): void
  {
    header("Location: {$this->url}{$route}");
    exit;
  }
}
