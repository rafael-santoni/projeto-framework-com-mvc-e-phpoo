<?php

namespace App\Session\Admin;

use App\Model\Entity\User as UserModel;

class Login
{
  /**
   * Método responsável por iniciar a sessão
   */
  private static function init(): void
  {
    //VERIFICA SE A SESSÃO NÃO ESTÁ ATIVA
    if(session_status() != PHP_SESSION_ACTIVE) {
      session_start();
    }
  }

  /**
   * Método responsável por criar o login do usuário
   * @param  UserModel $objUser
   * @return boolean
   */
  public static function login(UserModel $objUser): bool
  {
    //INICIA A SESSÃO
    self::init();

    //DEFINE A SESSÃO DO USUÁRIO
    $_SESSION['admin']['usuario'] = [
      'id' => $objUser->id,
      'nome' => $objUser->nome,
      'email' => $objUser->email
    ];

    //SUCESSO
    return true;
  }

  /**
   * Método responsável por verificar se o usuário está logado
   * @return boolean
   */
  public static function isLogged(): bool
  {
    //INICIA A SESSÃO
    self::init();

    //RETORNA A VERIFICAÇÃO
    return isset($_SESSION['admin']['usuario']['id']);
  }

  /**
   * Método responsável por executar o logout do usuário
   * @return boolean
   */
  public static function logout(): bool
  {
    //INICIA A SESSÃO
    self::init();

    //DESLOGA O USUÁRIO
    unset($_SESSION['admin']['usuario']);

    //SUCESSO
    return true;
  }
}
