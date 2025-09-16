<?php

namespace App\Infrastructure\Http\Response;

use App\Http\HttpStatus;

abstract class Response
{
  protected int $httpCode;
  protected array $headers = [];
  protected string $contentType;
  protected mixed $content;

  /**
   * @param int $httpCode
   * @param mixed $content
   * @param string $contentType
   * @return void
   */
  public function __construct(HttpStatus|int $httpCode = 200, mixed $content = '', string $contentType = 'text/html')
  {
    $this->httpCode = ($httpCode instanceof HttpStatus) ?  $httpCode->value : $httpCode;
    $this->content = $content;
    $this->setContentType($contentType);
  }

  /**
   * @param string $contentType
   * @return void
   */
  public function setContentType(string $contentType): void
  {
    $this->contentType = $contentType;
    $this->addHeader('Content-Type', $contentType);
  }

  /**
   * @param string $key
   * @param string $value
   * @return void
   */
  public function addHeader(string $key, string $value): void
  {
    $this->headers[$key] = $value;
  }

  /**
   * @return void
   */
  public function send(): void
  {
    http_response_code($this->httpCode);

    foreach ($this->headers as $key => $value) {
      header("$key: $value");
    }

    $this->sendContent();
  }

  

  /**
   * @return void
   */
  abstract protected function sendContent(): void;
}
