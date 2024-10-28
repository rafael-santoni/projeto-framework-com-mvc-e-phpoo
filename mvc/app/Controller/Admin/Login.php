<?php

namespace App\Controller\Admin;

use App\Http\Request;
use App\Controller\Admin\Page as PageController;
use App\Model\Entity\User as UserModel;
use App\Session\Admin\Login as LoginSession;
use App\Utils\View;

class Login extends PageController
{
  /**
   * Método responsável por retornar a renderização da página de login
   * @param  Request $request
   * @return string
   */
  public static function getLogin(Request $request, string $errorMessage = null): string
  {
    //STATUS
    $status = (!is_null($errorMessage)) ?
      View::render('admin/login/status', [
        'mensagem' => $errorMessage,
      ]) :
      '';

    //CONTEÚDO DA PÁGINA DE LOGIN
    $content = View:: render('admin/login', [
      'status' => $status,
    ]);

    //RETORNA A PÁGINA COMPLETA
    return parent::getPage('LOGIN | RS-Dev', $content);
  }

  /**
   * Método responsável por definir o login do usuário
   * @param  Request $request
   * @return string
   */
  public static function setLogin(Request $request): string
  {
    //POST VARS
    $postVars = $request->getPostVars();
    $email = $postVars['email'] ?? '';
    $senha = $postVars['senha'] ?? '';

    //BUSCA O USUÁRIO PELO E-MAIL
    $objUser = UserModel::getUserByEmail($email);
    // if(!$objUser) {
    if(!$objUser instanceof UserModel) {
      return self::getLogin($request, 'E-mail ou senha inválidos');
    }

    //VERIFICA A SENHA DO USUÁRIO
    if(!password_verify($senha, $objUser->senha)) {
      return self::getLogin($request, 'E-mail ou Senha inválidos');
    }

    //CRIA A SESSÃO DE LOGIN
    LoginSession::login($objUser);

    //REDIRECIONA O USUÁRIO PARA A HOME DO ADMIN
    $request->getRouter()->redirect('/admin');
  }

  /**
   * Método responsável por delogar o usuário
   * @param Request $request
   */
  public static function setLogout(Request $request): void
  {
    //DESTRÓI A SESSÃO DE LOGIN
    LoginSession::logout();

    //REDIRECIONA O USUÁRIO PARA A TELA DE LOGIN
    $request->getRouter()->redirect('/admin/login');
  }
}
