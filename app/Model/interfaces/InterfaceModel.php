<?php

namespace App\Model\interfaces;

interface InterfaceModel {

  public static function cadastrar(array $dados);
  
  public function delete();
}