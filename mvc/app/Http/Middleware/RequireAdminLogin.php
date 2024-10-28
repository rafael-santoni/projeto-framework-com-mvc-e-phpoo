<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Http\Response;
use App\Session\Admin\Login as LoginSession;
use \Closure;
use \Exception;

class RequireAdminLogin
{
  /**
   * Método responsável por executar o middleware
   * @param  Request  $request
   * @param  Closure  $next
   * @return Response
   */
  public function handle(Request $request, Closure $next): Response
  {
    //VEFIFICA SE O USUÁRIO NÃO ESTÁ LOGADO
    if(!LoginSession::isLogged()) {
      $request->getRouter()->redirect('/admin/login');
    }

    //CONTINUA A EXECUÇÃO
    return $next($request);
  }
}
