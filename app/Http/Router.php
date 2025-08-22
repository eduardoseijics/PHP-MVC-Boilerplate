<?php

namespace App\Http;

use \Closure;
use Exception;
use App\Http\Request;
use ReflectionFunction;
use App\Http\PageNotFound;
use App\Http\Middlewares\Queue as MiddlewareQueue;

class Router {

  /**
   * Url
   * @var string
   */
  private $url = '';

  /**
   * Route prefix
   * @var string
   */
  private $prefix = '';

  
  /**
   * Route package
   * @var string
   */
  private $package = '';

  /**
   * Route definitions
   * @var array
   */
  private $routes = [];

  /**
   * Http request
   * @var Request
   */
  private $request;

  public function __construct($url) {
    $this->request = new Request($this);
    $this->url = $url;
    $this->setPrefix();
    $this->setPackage();
  }

  /**
   * Set route prefix
   * @return void
   */
  private function setPrefix(): void
  {
    $parseUrl = parse_url($this->url);

    $this->prefix = $parseUrl['path'] ?? '';
  }

  /**
   * Set the route package
   * @return void
   */
  public function setPackage(): void
  {
    $xUri = explode('/', $this->getUri());
    unset($xUri[0]);

    $package = current($xUri);

    if(empty(current($xUri))) $package = 'site';

    $this->package = $package;
  }

  /**
   * @param string $method
   * @param string $route
   * @param array $params
   */
  private function addRoute($method, $route, $params = []) {

    foreach ($params as $key => $value) {
      if($value instanceof Closure) {
        $params['controller'] = $value;
        unset($params[$key]);
        continue;
      }
    }

    $params['middlewares'] = $params['middlewares'] ?? [];

    $params['variables'] = [];

    $patternVariable = '/{(.*?)}/';

    if(preg_match_all($patternVariable, $route, $matches)) {
      $route = preg_replace($patternVariable,'(.*?)', $route);
      $params['variables'] = $matches[1];
    }

    $patternRoute = '/^'.str_replace('/','\/', $route).'$/';

    $this->routes[$patternRoute][$method] = $params;
  }

  /**
   * Add a GET route
   * @param string $route
   * @param array $params
   * @return void
   */
  public function get($route, $params = []): null
  {
    return $this->addRoute('GET', $route, $params);
  }

  /**
   * Add a POST route
   * @param string $route
   * @param array $params
   * @return void
   */
  public function post($route, $params = []): null
  {
    return $this->addRoute('POST', $route, $params);
  }

  /**
   * Add a PUT route
   * @param string $route
   * @param array $params
   * @return void
   */
  public function put($route, $params = []): null
  {
    return $this->addRoute('PUT', $route, $params);
  }

  /**
   * Add a DELETE route
   * @param string $route
   * @param array $params
   * @return void
   */
  public function delete($route, $params = []): null
  {
    return $this->addRoute('DELETE', $route, $params);
  }

  /**
   * 
   * @return string
   */
  private function getUri(): string
  {
    $uri = $this->request->getUri();
    $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

    return end($xUri);
  }

  /**
   * Get the current route
   * @return  array
   */
  private function getRoute(): array
  {
    $uri = $this->getUri();

    $httpMethod = $this->request->getHttpMethod();

    foreach ($this->routes as $patternRoute => $methods) {
      if(preg_match($patternRoute, $uri, $matches)) {
        // Check if the HTTP method is allowed
        if(isset($methods[$httpMethod])) {
          unset($matches[0]);

          // Map variables
          $keys                                         = $methods[$httpMethod]['variables'];
          $methods[$httpMethod]['variables']            = array_combine($keys, $matches);
          $methods[$httpMethod]['variables']['request'] = $this->request;

          return $methods[$httpMethod];
        }

        throw new Exception('Method not allowed', Response::HTTP_METHOD_NOT_ALLOWED);
      }
    }

    throw new Exception(PageNotFound::get404($this->package), Response::HTTP_NOT_FOUND);
  }

  public function group($middlewares) {
    
  }

  /**
   * 
   * @return Response
   */
  public function run(): Response
  {
    try {
      $route = $this->getRoute();
      
      if(!isset($route['controller'])) {
        throw new Exception('A URL não pode ser processada', Response::HTTP_INTERNAL_SERVER_ERROR);
      }

      $args = [];

      $reflection = new ReflectionFunction($route['controller']);
      foreach ($reflection->getParameters() as $parameter) {
        $name = $parameter->getName();
        $args[$name] = $route['variables'][$name] ?? '';
      }

      return (new MiddlewareQueue($route['middlewares'], $route['controller'], $args))->next($this->request);

      return call_user_func_array($route['controller'], $args);
    } catch (Exception $e) {
      return new Response($e->getCode(), $e->getMessage());
    }
  }

/**
 *
 * @return string
 */
  public function getCurrentUrl(): string {
    return $this->url . $this->getUri();
  }

  /**
   * 
   *
   * @param string $route
   * @return void
   */
  public function redirect($route)
  {
    $fullUrl = $this->url . $route;

    // 
    header("location: {$fullUrl}");
    exit;
  }

}