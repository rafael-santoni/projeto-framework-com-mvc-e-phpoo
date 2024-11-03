<?php

namespace App\Utils\Cache;

use \Closure;

class File
{
  /**
   * Método responsável por retornar o caminho até o arquivo de cache
   * @param string $hash
   * @return string
   */
  private static function getFilePath(string $hash): string
  {
    //DIRETÓRIO DE CACHE
    $dir = $_ENV['CACHE_DIR'];
    
    //VERIFICA A EXISTÊNCIA DO DIRETÓRIO
    if(!file_exists($dir)) {
      mkdir($dir, 0755, true);
    }

    //RETORNA O CAMINHO ATÉ O ARQUIVO
    return $dir . '/' . $hash;
  }

  /**
   * Método responsável por guardar/gravar informações no cache
   * @param string $hash
   * @param mixed $content
   * @return boolean
   */

  private static function storageCache(string $hash, mixed $content): bool
  {
    //SERIALIZA O RETORNO
    $serialize = serialize($content);
    
    //OBTÉM O CAMINHO ATÉ O ARQUIVO DE CACHE
    $cacheFile = self::getFilePath($hash);

    //GRAVA AS INFORMAÇÕES NO ARQUIVO
    return file_put_contents($cacheFile, $serialize);
  }

  /**
   * Método responsável por retornar o conteúdo gravado no cache
   * @param string $hash
   * @param integer $expiration
   * @return mixed
   */
  private static function getContentCache(string $hash, int $expiration): mixed
  {
    //OBTÉM O CAMINHO DO ARQUIVO
    $cacheFile = self::getFilePath($hash);
    
    //VERIFICA A EXISTÊNCIA DO ARQUIVO
    if(!file_exists($cacheFile)) {
      return false;
    }

    //VALIDA A EXPIRAÇÃO DO CACHE
    $createTime = filemtime($cacheFile);
    $diffTime = time() - $createTime;
    if($diffTime > $expiration) {
      return false;
    }

    $cacheContent = file_get_contents($cacheFile);
    return unserialize($cacheContent);
  }

  /**
   * Método responsável por obter uma informação do cache
   * @param string $hash
   * @param integer $expiration
   * @param Closure $function
   * @return mixed
   */
  public static function getCache(string $hash, int $expiration, Closure $function): mixed
  {
    //VERIFICA O CONTEÚDO GRAVADO
    if($content = self::getContentCache($hash, $expiration)) {
      return $content;
    }

    //EXECUÇÃO DA FUNÇÃO (CLOSURE)
    $content = $function();

    self::storageCache($hash, $content);

    //RETORNA O CONTEÚDO
    return $content;
  }
}
