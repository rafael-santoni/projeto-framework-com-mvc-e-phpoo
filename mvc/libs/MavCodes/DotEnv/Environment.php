<?php

namespace Libs\MavCodes\DotEnv;

class Environment
{
  /**
   * Método responsável por carregar as variáveis de ambiente o projeto
   * @param string $dir - Caminho absoluto da pasta onde encontra-se o arquivo .env
   * @return bool
   */
  public static function load(string $dir): bool
  {
    //VERIFICA SE O ARQUIVO .ENV EXISTE
    if(!file_exists($dir.'/.env')) {
      return false;
    }

    //DEFINE AS VARIÁVEIS DE AMBIENTE
    $lines = file($dir.'/.env');

    foreach ($lines as $line) {
      putenv(trim($line));
      [$lineKey, $lineValue] = explode('=', trim($line));
      $_ENV[$lineKey] = $lineValue;
    }

    return true;
  }
}
