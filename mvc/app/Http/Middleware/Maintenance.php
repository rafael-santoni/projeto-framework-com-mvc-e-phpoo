<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Http\Response;
use \Closure;
use \Exception;

class Maintenance
{
  /**
   * Método responsável por executar o middleware
   * @param  Request  $request
   * @param  Closure  $next
   * @return Response
   */
  public function handle(Request $request, Closure $next): Response
  {
    //VERIFICA O ESTADO DE MANUTENÇÃO DA PÁGINA
    if($_ENV['MAINTENANCE'] == 'true') {
      throw new Exception("Página em manutenção. Tente novamente mais tarde.", 200);
    }

    //EXECUTA O PRÓXIMO NÍVEL DO MIDDLEWARE
    return $next($request);
  }
}
