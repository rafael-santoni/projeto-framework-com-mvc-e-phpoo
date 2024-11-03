<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Http\Response;
use App\Utils\Cache\File as CacheFile;
use \Closure;
use \Exception;

class Cache
{
  /**
   * Método responsável por verificar se a requisição pode ser cacheada
   * @param Request $request
   * @return boolean
   */
  private static function isCacheable(Request $request): bool
  {
    //VALIDA O TEMPO DE CACHE
    if($_ENV['CACHE_TIME'] <= 0) {
      return false;
    }

    //VALIDA O MÉTODO DA REQUISIÇÃO
    if($request->getHttpMethod() != 'GET') {
      return false;
    }

    //VALIDA O HEADER DE CACHE
    $headers = $request->getHeaders();
    if(isset($headers['Cache-Control']) && $headers['Cache-Control'] == 'no-cache') {
      return false;
    }

    //CACHEÁVEL
    return true;
  }

  /**
   * Método responsável por retornar a hash do cache
   * @param Request $request
   * @return string
   */
  private static function getHash(Request $request): string
  {
    //URI DA ROTA
    $uri = $request->getRouter()->getUri();
    
    //QUERY PARAMS
    $queryParams = $request->getQueryParams();
    $uri .= !empty($queryParams) ? '?' . http_build_query($queryParams) : '';

    //REMOVE AS BARRAS E RETORNA A HASH
    return rtrim('route-' . preg_replace('/[^0-9a-zA-Z]/', '-', ltrim($uri, '/')), '-');
  }

  /**
   * Método responsável por executar o middleware
   * @param  Request  $request
   * @param  Closure  $next
   * @return Response
   */
  public function handle(Request $request, Closure $next): Response
  {
    //VERIFICA SE A REQUISIÇÃO ATUAL É CACHEÁVEL
    if(!$this->isCacheable($request)) return $next($request);

    //HASH DO CACHE
    $hash = $this->getHash($request);

    //RETORNA OS DADOS DO CACHE
    return CacheFile::getCache($hash, $_ENV['CACHE_TIME'], function() use($request, $next) {
      // EXECUTA O PRÓXIMO NÍVEL DO MIDDLEWARE
      return $next($request);
    });

  }
}
