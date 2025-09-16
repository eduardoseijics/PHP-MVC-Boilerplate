<?php

namespace App\Infrastructure\Http\Response;

use App\Infrastructure\Http\Response\Response;

class RedirectResponse extends Response
{
  public function __construct(string $location, int $status = 302, array $headers = [])
  {
    parent::__construct($status, '', 'text/html');
    $this->addHeader('Location', URL.$location);
    foreach ($headers as $k => $v) {
      $this->addHeader($k, $v);
    }
  }

  protected function sendContent(): void
  {
    exit;
  }
}
