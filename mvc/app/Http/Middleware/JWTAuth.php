<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Http\Response;
use App\Model\Entity\User as UserModel;
use \Closure;
use \Exception;
use Firebase\JWT\JWT;

class JWTAuth
{
  /**
   * Método responsável por retornar uma instância de usuário autenticado
   * @param Request $request
   * @return boolean|User
   */
  private function getJWTAuthUser(Request $request): bool|UserModel
  {
    //HEADERS
    $headers = $request->getHeaders();

    //TOKEN PURO EM JWT
    $jwt = (isset($headers['Authorization'])) ? str_replace('Bearer ', '', $headers['Authorization']) : '';

    try {
      //DECODE
      $decode = (array)JWT::decode($jwt, $_ENV['JWT_KEY'], ['HS256']);
    } catch (Exception $e) {
      throw new Exception('Token Inválido', 403);
    }

    //E-MAIL
    $email = $decode['email'] ?? '';

    //BUSCA O USUÁRIO PELO E-MAIL
    $objUser = UserModel::getUserByEmail($email);
    
    //RETORNA O USUÁRIO
    return ($objUser instanceof UserModel) ? $objUser : false;
  }

  /**
   * Método responsável por validar o acesso via JWT
   * @param Request $request
   * @return boolean
   */
  private function JWTAuth(Request $request): bool
  {
    //VERIFICA O USUÁRIO RECEBIDO
    if($objUser = $this->getJWTAuthUser($request)) {
      $request->user = $objUser;
      return true;
    }

    //EMITE O ERRO DE SENHA INVÁLIDA
    throw new Exception('Acesso negado!', 403);
  }

  /**
   * Método responsável por executar o middleware
   * @param  Request  $request
   * @param  Closure  $next
   * @return Response
   */
  public function handle(Request $request, Closure $next): Response
  {
    //REALIZA A VALIDAÇÃO DO ACESSO VIA JWT
    $this->JWTAuth($request);

    //EXECUTA O PRÓXIMO NÍVEL DO MIDDLEWARE
    return $next($request);
  }
}