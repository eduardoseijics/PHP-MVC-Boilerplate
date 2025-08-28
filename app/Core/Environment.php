<?php

namespace App\Core;

class Environment {

  /**
   * Load environment variables from .env file
   * @param string $dir Absolute path to the directory containing the .env file
   * @return void
   */
  public static function load($dir){
    // Check if the .env file exists
    if(!file_exists($dir.'/.env')){
      return false;
    }

    // Define environment variables
    $lines = file($dir.'/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    $lines = self::sanitizeEnvLines($lines);

    foreach($lines as $line){
      putenv(trim($line));
    }
  }

  /**
   * Remove comments and empty lines from the array
   * @param array $lines
   * @return array
   */
  public static function sanitizeEnvLines(array $lines) {
    $sanitizedLines = array_map('trim', $lines);
    $sanitizedLines = array_filter($sanitizedLines);

    $linesWithoutComments = array_filter($sanitizedLines, function($line) {
      return (strpos(trim($line), '#') !== 0);
    });

    return $linesWithoutComments;
  }
}