<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Http\Response;
use \Closure;

class Api
{
  /**
   * Método responsável por executar o middleware
   * @param  Request  $request
   * @param  Closure  $next
   * @return Response
   */
  public function handle(Request $request, Closure $next): Response
  {
    //ALTERA O CONTENT TYPE PARA JSON
    $request->getRouter()->setContentType('application/json');

    //EXECUTA O PRÓXIMO NÍVEL DO MIDDLEWARE
    return $next($request);
  }
}
