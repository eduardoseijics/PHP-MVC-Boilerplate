<?php

namespace App\Core;

class Environment {

  /**
   * Método responsável por carregar as variáveis de ambiente do projeto
   * @param string $dir Caminho absoluto da pasta onde encontra-se o arquivo .env
   * @return void
   */
  public static function load($dir){
    //VERIFICA SE O ARQUIVO .ENV EXISTE
    if(!file_exists($dir.'/.env')){
      return false;
    }

    //DEFINE AS VARIÁVEIS DE AMBIENTE
    $linhas = file($dir.'/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    $linhas = self::sanitizaLinhasEnv($linhas);

    foreach($linhas as $linha){
      putenv(trim($linha));
    }
  }

  /**
   * Responsavel por remover os comentarios e linhas em branco do array
   * @param array $linhas
   * @return array
   */
  public static function sanitizaLinhasEnv(array $linhas) {
    $linhasSanitizadas = array_map('trim', $linhas);
    $linhasSanitizadas = array_filter($linhasSanitizadas);

    $linhasSemComentarios = array_filter($linhasSanitizadas, function($linha) {      
      return (strpos(trim($linha), '#') !== 0);
    });

    return $linhasSemComentarios;
  }
}