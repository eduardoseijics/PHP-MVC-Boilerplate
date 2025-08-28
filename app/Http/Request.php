<?php

namespace App\Http;

class Request {

  /**
   * 
   * @var string
   */
  private $router;

  /**
   * 
   * @var string
   */
  private $httpMethod;

  /**
   * 
   * @var string
   */
  private $uri;

  /**
   * 
   * @var array
   */
  private $queryParams = [];

  /**
   * 
   * @var array
   */
  private $postVars = [];

  /**
   * Request headers
   * @var array
   */
  private $headers = [];

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