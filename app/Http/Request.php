<?php

namespace App\Http;

class Request {

  /**
   * Método HTTP da requisição
   * @var string
   */
  private $router;

  /**
   * Método HTTP da requisição
   * @var string
   */
  private $httpMethod;

  /**
   * URI da página
   * @var string
   */
  private $uri;

  /**
   * Parâmetros da URL ($_GET)
   * @var array
   */
  private $queryParams = [];

  /**
   * Váriaveis recebidas no POST da página ($_POST)
   * @var array
   */
  private $postVars = [];

  /**
   * Cabeçalho da requisição
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
   * Definir URI
   * @return void
   */
  private function setUri() {
    // URI completo (com parâmetros)
    $fullUri = $_SERVER['REQUEST_URI'] ?? '';

    // Removendo parâmetros do URI
    $this->uri = explode('?', $fullUri)[0];
  }

  /**
   * Método responsável por retornar o método HTTP da requisição
   * @return string
   */
  public function getHttpMethod() {
    return $this->httpMethod;
  }

  /**
   * Retorna a instância da classe Router utilizada pelo request
   *
   * @return Router
   */
  public function getRouter() {
    return $this->router;
  }

  /**
   * Método responsável por retornar o método HTTP da requisição
   * @return string
   */
  public function getUri() {
    return $this->uri;
  }

  /**
   * Método responsável por retornar as variáveis POST da requisição
   * @return array
   */
  public function getPostVars() {
    return $this->postVars;
  }
  
  /**
   * Método responsável por retornar os parâmetros da URL da requisição
   * @return array
   */
  public function getQueryParams() {
    return $this->queryParams;
  }

  
  /**
   * Método responsável por retornar os headers da requisição
   * @return string
   */
  public function getHeaders() {
    return $this->headers;
  }
}