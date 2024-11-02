<?php

namespace App\Controller\Api;

use App\Http\Request;
use App\Controller\Api\Api as ApiController;
use App\Model\Entity\User as UserModel;
use \Exception;
use Firebase\JWT\JWT;

class Auth extends ApiController
{
  /**
   * Método responsável por gerar um token JWT
   * @param Request $request
   * @return array
   */
  public static function generateToken(Request $request): array
  {
    //POST VARS
    $postVars = $request->getPostVars();
    
    //VALIDA OS CAMPOS OBRIGATÓRIOS
    if(!isset($postVars['email']) || !isset($postVars['senha'])) {
      throw new Exception("Os campos 'email' e 'senha' são obrigatórios", 400);
    }

    //BUSCA O USUÁRIOS PELO E-MAIL
    $objUser = UserModel::getUserByEmail($postVars['email']);
    if(!$objUser instanceof UserModel) {
      throw new Exception('Usuário ou senha inválidos', 403);
    }
    
    //VALIDA A SENHA DO USUÁRIO
    if(!password_verify($postVars['senha'], $objUser->senha)) {
      throw new Exception('Usuário ou senha inválidos', 403);
    }

    //PAYLOAD
    $payload = [
      'email' => $objUser->email
    ];

    //RETORNA O TOKEN GERADO
    return [
      'token' => JWT::encode($payload, $_ENV['JWT_KEY'])
    ];
  }
}
