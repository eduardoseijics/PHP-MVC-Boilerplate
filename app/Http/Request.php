<?php

namespace App\Http;

class Request {

  /**
   * Router instance
   * @var string
   */
  private $router;

  /**
   * HTTP method
   * @var string
   */
  private $httpMethod;

  /**
   * URI
   * @var string
   */
  private $uri;

  /**
   * Query parameters
   * @var array
   */
  private $queryParams = [];

  /**
   * POST variables
   * @var array
   */
  private $postVars = [];

  /**
   * Headers
   * @var array
   */
  private $headers = [];

  /**
   * Constructor
   * @param Router $router
   */
  public function __construct($router) {
    $this->router      = $router;
    $this->queryParams = $_GET ?? [];
    $this->postVars    = $_POST ?? [];
    $this->headers     = getallheaders();
    $this->httpMethod  = $_SERVER['REQUEST_METHOD'] ?? '';
    $this->setUri();
  }

  /**
   * Set the URI of the request
   * @return void
   */
  private function setUri() {

    $fullUri = $_SERVER['REQUEST_URI'] ?? '';

    // Removing parameters from the URI
    $this->uri = explode('?', $fullUri)[0];
  }

  /**
   * Get HTTP method
   * @return string
   */
  public function getHttpMethod() {
    return $this->httpMethod;
  }

  /**
   * Get the instance of the Router class used by the request
   * @return Router
   */
  public function getRouter() {
    return $this->router;
  }

  /**
   * Get the URI of the request
   * @return string
   */
  public function getUri() {
    return $this->uri;
  }

  /**
   * Get the POST variables from the request
   * @return array
   */
  public function getPostVars() {
    return $this->postVars;
  }

  /**
   * Get a specific POST parameter from the request
   * @param string $param
   * @return mixed
   */
  public function getPostParam(string $param): mixed
  {
    return $this->postVars[$param] ?? null;
  }

  /**
   * Get a specific query parameter from the request
   * @param string $param
   * @return mixed
   */
  public function getQueryParam(string $param): mixed
  {
    return $this->queryParams[$param] ?? null;
  }
  
  /**
   * Get the query parameters from the request
   * @return array
   */
  public function getQueryParams() {
    return $this->queryParams;
  }

  /**
  * Get the headers from the request
   * @return string
   */
  public function getHeaders() {
    return $this->headers;
  }
}