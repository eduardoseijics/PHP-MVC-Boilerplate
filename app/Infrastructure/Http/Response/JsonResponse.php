<?php

namespace App\Infrastructure\Http\Response;

use App\Infrastructure\Http\Response\Response;

class JsonResponse extends Response
{
  public function __construct(array $data, int $status = 200, array $headers = [])
  {
    parent::__construct($status, json_encode($data, JSON_UNESCAPED_UNICODE), 'application/json');
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
