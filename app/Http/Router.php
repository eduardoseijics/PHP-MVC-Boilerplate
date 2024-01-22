<?php

namespace App\Http;

use \Closure;
use Exception;
use App\Http\Request;
use ReflectionFunction;
use App\Http\PageNotFound;
use App\Controller\Pages\Page;
use App\Http\Middlewares\Queue as MiddlewareQueue;

class Router {

  /**
   * URL completa do projeto (raiz)
   * @var string
   */
  private $url = '';

  /**
   * Prefixo de todas as rotas
   * @var string
   */
  private $prefix = '';

  
  /**
   * Modulo atual
   * @var string
   */
  private $package = '';

  /**
   * Índice de rotas
   * @var array
   */
  private $routes = [];

  /**
   * Instância de Request
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
   * Método responsável por definir o prefixo das rotas
   */
  private function setPrefix() {
    $parseUrl = parse_url($this->url);

    $this->prefix = $parseUrl['path'] ?? '';
  }

  public function setPackage() {
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
    // VALIDAÇÃO DOS PARÂMETROS 
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
   * Responsável por definir uma rota de GET
   * @param string $route
   * @param array $params
   * @return void
   */
  public function get($route, $params = []) {
    return $this->addRoute('GET', $route, $params);
  }

  /**
   * Responsável por definir uma rota de POST
   * @param string $route
   * @param array $params
   * @return void
   */
  public function post($route, $params = []) {
    return $this->addRoute('POST', $route, $params);
  }

  /**
   * Responsável por definir uma rota de PUT
   * @param string $route
   * @param array $params
   * @return void
   */
  public function put($route, $params = []) {
    return $this->addRoute('PUT', $route, $params);
  }

  /**
   * Responsável por definir uma rota de DELETE
   * @param string $route
   * @param array $params
   * @return void
   */
  public function delete($route, $params = []) {
    return $this->addRoute('DELETE', $route, $params);
  }

  /**
   * Responsável por retornar a URI desconsiderando o prefixo
   * @return string
   */
  private function getUri() {
    $uri = $this->request->getUri();
    $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

    return end($xUri);
  }

  /**
   * Responsável por retornar os dados da rota atual
   * @return  array
   */
  private function getRoute() {
    $uri = $this->getUri();

    $httpMethod = $this->request->getHttpMethod();

    foreach ($this->routes as $patternRoute => $methods) {
      if(preg_match($patternRoute, $uri, $matches)) {
        //VERIFICA O METODO
        if(isset($methods[$httpMethod])) {
          unset($matches[0]);

          //VARIAVEIS PROCESSADAS 
          $keys = $methods[$httpMethod]['variables'];
          $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
          $methods[$httpMethod]['variables']['request'] = $this->request;

          return $methods[$httpMethod];
        }

        throw new Exception('Método não é permitido', Response::HTTP_METHOD_NOT_ALLOWED);
      }
    }

    throw new Exception(PageNotFound::get404($this->package), Response::HTTP_NOT_FOUND);
  }

  public function group($middlewares) {
    
  }

  /**
   * Método responsável por executar a rota atual
   * @return Response
   */
  public function run() {
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
 * Retorna o URL atual
 *
 * @return string
 */
  public function getCurrentUrl() {
    return $this->url . $this->getUri();
  }

  /**
   * Redirecionar URL
   *
   * @param string $route Endpoint para redirecionamento
   * @return void
   */
  public function redirect($route)
  {
    $fullUrl = $this->url . $route;

    // Executa o redirect
    header("location: {$fullUrl}");
    exit;
  }

}