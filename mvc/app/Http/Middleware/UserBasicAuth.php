<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Http\Response;
use App\Model\Entity\User as UserModel;
use \Closure;
use \Exception;

class UserBasicAuth
{
  /**
   * Método responsável por retornar uma instância de usuário autenticado
   * @return boolean|User
   */
  private function getBasicAuthUser(): bool|UserModel
  {
    //VERIFICA A EXISTÊNCIA DOS DADOS DE ACESSO
    if(!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
      return false;
    }

    //BUSCA O USUÁRIO PELO E-MAIL
    $objUser = UserModel::getUserByEmail($_SERVER['PHP_AUTH_USER']);
    
    //VERIFICA A INSTÂNCIA
    if(!$objUser instanceof UserModel) {
      return false;
    }

    //VALIDA A SENHA E RETORNA O USUÁRIO
    return (password_verify($_SERVER['PHP_AUTH_PW'], $objUser->senha)) ? $objUser : false;
  }

  /**
   * Método responsável por validar o acesso via HTTP Basic Auth
   * @param Request $request
   * @return boolean
   */
  private function basicAuth(Request $request): bool
  {
    //VERIFICA O USUÁRIO RECEBIDO
    if($objUser = $this->getBasicAuthUser()) {
      $request->user = $objUser;
      return true;
    }

    //EMITE O ERRO DE SENHA INVÁLIDA
    throw new Exception('Usuário ou senha inválidos', 403);
  }

  /**
   * Método responsável por executar o middleware
   * @param  Request  $request
   * @param  Closure  $next
   * @return Response
   */
  public function handle(Request $request, Closure $next): Response
  {
    //REALIZA A VALIDAÇÃO DO ACESSO VIA BASIC AUTH
    $this->basicAuth($request);

    //EXECUTA O PRÓXIMO NÍVEL DO MIDDLEWARE
    return $next($request);
  }
}