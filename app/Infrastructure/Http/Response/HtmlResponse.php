<?php

namespace App\Infrastructure\Http\Response;

use App\Infrastructure\Http\Response\Response;

class HtmlResponse extends Response
{
  public function __construct(string $content, int $status = 200, array $headers = [])
  {
    parent::__construct($status, $content, 'text/html');
    foreach ($headers as $k => $v) {
      $this->addHeader($k, $v);
    }
  }

  protected function sendContent(): void
  {
    echo $this->content;
    exit;
  }
}
